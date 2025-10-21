<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use Throwable;

class InvoiceImportController extends Controller
{
  public function store(Request $request)
{
    try {
        $type  = $request->input('type'); // 'sales' or 'purchase'
        $items = $request->input('items');

        if (!in_array($type, ['sales','purchase'], true)) {
            return response()->json(['ok'=>false,'stage'=>'type_check','error'=>'type must be sales|purchase'], 422);
        }
        if (!is_array($items) || count($items) < 1) {
            return response()->json(['ok'=>false,'stage'=>'items_check','error'=>'items must be a non-empty array'], 422);
        }

        // Allowed enums in YOUR validator (edit to your exact allowed sets)
        $ALLOWED_VENDOR = ['R','UR','REGISTERED','UNREGISTERED'];      // map to R/UR
        $ALLOWED_INVOICE = ['B2B','B2C','IMP','SEZ','EXEMPT'];         // map to these

        $stateMap = [
            'kerala'=>'KL','kl'=>'KL','karnataka'=>'KA','ka'=>'KA','tamil nadu'=>'TN','tn'=>'TN',
            'maharashtra'=>'MH','mh'=>'MH','andhra pradesh'=>'AP','ap'=>'AP','telangana'=>'TS','ts'=>'TS',
            'delhi'=>'DL','dl'=>'DL','gujarat'=>'GJ','gj'=>'GJ','outside india'=>'OI','oi'=>'OI'
        ];
        $toState = function($s) use ($stateMap) {
            $s = trim((string)($s ?? ''));
            $k = strtolower($s);
            if (isset($stateMap[$k])) return $stateMap[$k];
            // fallback: first 2 letters uppercased
            return strtoupper(substr($s, 0, 2));
        };

        $normalizeYesNoBool = fn($v) => in_array($v, [true,1,'1','true','TRUE','yes','YES'], true);

        $ok = 0; $errors = []; $created = [];

        foreach ($items as $idx => $raw) {
            // --- normalize keys to what your rules expect ---
            $x = $raw;

            // Party & GSTIN (align to your validator)
            $x['party_name'] = $x['party_name']
                ?? $x['supplier_name']
                ?? $x['customer_name']
                ?? null;
            $x['gstin'] = $x['gstin']
                ?? $x['supplier_gstin']
                ?? $x['customer_gstin']
                ?? null;

            // Dates: accept DD/MM/YYYY or YYYY-MM-DD
            $x['invoice_date'] = $this->normalizeDate($x['invoice_date'] ?? null);

            // State codes (2 letters)
            $x['place_of_supply'] = $toState($x['place_of_supply'] ?? '');
            $x['origin_state']    = $toState($x['origin_state'] ?? '');

            // Reverse charge boolean
            $x['reverse_charge'] = $normalizeYesNoBool($x['reverse_charge'] ?? false);

            // quantity / money types
            $x['qty'] = (int)($x['qty'] ?? 1);
            $x['unit_price'] = isset($x['unit_price']) ? (float)$x['unit_price'] : null;
            $x['tax_inclusive'] = $normalizeYesNoBool($x['tax_inclusive'] ?? false);
            $x['tax_rate'] = (float)($x['tax_rate'] ?? 0);
            $x['taxable_value'] = (float)($x['taxable_value'] ?? 0);
            $x['round_off'] = (float)($x['round_off'] ?? 0);

            // Vendor/Invoice type coercion for purchases (configure to YOUR enums)
            if ($type === 'purchase') {
                // vendor_type -> R/UR
                $vt = strtoupper((string)($x['vendor_type'] ?? 'R'));
                if (!in_array($vt, $ALLOWED_VENDOR, true)) {
                    // try to coerce common forms
                    if (in_array($vt, ['REGISTERED','R'], true)) $vt = 'R';
                    elseif (in_array($vt, ['UNREGISTERED','UR'], true)) $vt = 'UR';
                }
                $x['vendor_type'] = $vt;

                // invoice_type -> allowed set caps
                $it = strtoupper((string)($x['invoice_type'] ?? 'B2B'));
                $map = ['IMPORT'=>'IMP','SEZ'=>'SEZ','B2B'=>'B2B','B2C'=>'B2C','EXEMPTED'=>'EXEMPT'];
                $it = $map[$it] ?? $it;
                if (!in_array($it, $ALLOWED_INVOICE, true)) $it = 'B2B';
                $x['invoice_type'] = $it;
            } else {
                // sales defaults (if your validator expects caps)
                $it = strtoupper((string)($x['invoice_type'] ?? 'B2C'));
                $map = ['SEZ'=>'SEZ','EXPORT'=>'IMP','B2B'=>'B2B','B2C'=>'B2C','EXEMPTED'=>'EXEMPT'];
                $x['invoice_type'] = $map[$it] ?? $it;
            }

            // Compute supply_type + tax split server-side (never trust client)
            $computed = $this->computeTax($type, $x);

            // Validate against your rules (replace with your FormRequest if you have one)
            $rules = $this->rulesMatchingYourApp($type);
            $v = \Validator::make(['items'=>[$computed]], $rules);
            if ($v->fails()) {
                $errors[] = [
                    'row' => $idx+1,
                    'errors' => $v->errors()->toArray(),
                    'data' => $computed,
                ];
                continue;
            }

            // Persist (make sure $fillable on your models include these columns!)
            if ($type === 'purchase') {
                $m = \App\Models\PurchaseInvoice::create($computed);
                // Optional: classify for 3B buckets if you use it
                if (class_exists(\App\Support\Gst3BClassifier::class)) {
                    $bucket = \App\Support\Gst3BClassifier::classify($m);
                    $m->update(['itc_bucket_code'=>$bucket['code'], 'itc_bucket_label'=>$bucket['label']]);
                }
            } else {
                $m = \App\Models\SalesInvoice::create($computed);
            }

            $ok++;
            $created[] = $m->id;
        }

        return response()->json([
            'ok' => $ok > 0 && count($errors) === 0,
            'saved' => $ok,
            'failed' => count($errors),
            'ids' => $created,
            'errors' => $errors,
            'stage' => 'done'
        ], (count($errors) ? 207 : 200)); // 207 = Multi-Status-ish
    } catch (\Throwable $e) {
        \Log::error('Import crash', ['ex'=>$e, 'trace'=>$e->getTraceAsString()]);
        return response()->json([
            'ok' => false,
            'stage' => 'crash',
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}

// Helpers (same class)
private function normalizeDate(?string $d): ?string {
    if (!$d) return null;
    $d = trim($d);
    if (preg_match('~^\d{2}/\d{2}/\d{4}$~', $d)) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $d)->format('Y-m-d');
    }
    return \Carbon\Carbon::parse($d)->format('Y-m-d');
}

private function rulesMatchingYourApp(string $type): array {
    // Mirror your app’s FormRequest. Example (note the party_name/gstin):
    $base = [
        'items' => 'required|array|min:1',
        'items.*.invoice_no'   => 'required|string|max:50',
        'items.*.invoice_date' => 'required|date',
        'items.*.hsn'          => 'nullable|string|max:20',
        'items.*.party_name'   => 'required|string|max:190',
        'items.*.gstin'        => 'nullable|string|max:15',
        'items.*.place_of_supply' => 'required|string|max:2',
        'items.*.origin_state'    => 'required|string|max:2',
        'items.*.reverse_charge'  => 'required|boolean',
        'items.*.qty'             => 'required|integer|min:1',
        'items.*.uom'             => 'nullable|string|max:20',
        'items.*.unit_price'      => 'nullable|numeric|min:0',
        'items.*.tax_inclusive'   => 'required|boolean',
        'items.*.taxable_value'   => 'required|numeric|min:0',
        'items.*.tax_rate'        => 'required|numeric|min:0|max:100',
        'items.*.round_off'       => 'nullable|numeric',
    ];
    if ($type === 'purchase') {
        $base['items.*.vendor_type']  = 'required|in:R,UR';                // edit to match your exact enums
        $base['items.*.invoice_type'] = 'required|in:B2B,B2C,IMP,SEZ,EXEMPT'; // edit to match your exact enums
    } else {
        $base['items.*.invoice_type'] = 'required|in:B2B,B2C,SEZ,EXEMPT';   // edit as needed
    }
    return $base;
}

private function computeTax(string $type, array $x): array {
    $d = $x;

    // supply type
    $st = 'intra';
    $origin = strtoupper($x['origin_state'] ?? '');
    $pos    = strtoupper($x['place_of_supply'] ?? '');
    // IMP/SEZ treated as inter
    if (($type === 'purchase' && in_array($x['invoice_type'] ?? '', ['IMP','SEZ'], true)) ||
        ($type === 'sales'    && in_array($x['invoice_type'] ?? '', ['IMP','SEZ'], true))) {
        $st = 'inter';
    } else if ($origin && $pos && $origin !== $pos) {
        $st = 'inter';
    }
    $d['supply_type'] = $st;

    $qty  = max(1, (int)($x['qty'] ?? 1));
    $rate = (float)($x['tax_rate'] ?? 0);
    $up   = isset($x['unit_price']) ? (float)$x['unit_price'] : 0.0;
    $tv   = (float)($x['taxable_value'] ?? 0.0);
    $incl = (bool)($x['tax_inclusive'] ?? false);

    if ($up > 0) {
        if ($incl) {
            $basePerUnit = $rate > 0 ? ($up / (1 + $rate/100)) : $up;
            $tv = $qty * $basePerUnit;
        } else {
            $tv = $qty * $up;
        }
    }
    // split
    if ($st === 'intra') { $cgst_r = $rate/2; $sgst_r = $rate/2; $igst_r = 0; }
    else { $cgst_r = 0; $sgst_r = 0; $igst_r = $rate; }

    $cgst_a = $tv * ($cgst_r/100);
    $sgst_a = $tv * ($sgst_r/100);
    $igst_a = $tv * ($igst_r/100);
    $tax_a  = $cgst_a + $sgst_a + $igst_a;

    if ($up > 0 && $incl) {
        $gross = $qty * $up;
        $tax_a = $gross - $tv;
        if ($st === 'intra') { $cgst_a = $tax_a/2; $sgst_a = $tax_a/2; $igst_a = 0; }
        else { $cgst_a = 0; $sgst_a = 0; $igst_a = $tax_a; }
    }

    $d['cgst_rate'] = round($cgst_r,2);
    $d['sgst_rate'] = round($sgst_r,2);
    $d['igst_rate'] = round($igst_r,2);
    $d['cgst_amount'] = round($cgst_a,2);
    $d['sgst_amount'] = round($sgst_a,2);
    $d['igst_amount'] = round($igst_a,2);
    $d['tax_amount']  = round($tax_a,2);
    $d['total_invoice_value'] = round($tv + $tax_a + (float)($x['round_off'] ?? 0), 2);

    // booleans as booleans for validation
    $d['tax_inclusive'] = (bool)$x['tax_inclusive'];
    $d['reverse_charge'] = (bool)$x['reverse_charge'];

    return $d;
}

}