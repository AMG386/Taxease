<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Support\Gst3BClassifier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = PurchaseInvoice::query()
            ->when($request->filled('from'), fn($x) => $x->whereDate('invoice_date', '>=', $request->date('from')))
            ->when($request->filled('to'),   fn($x) => $x->whereDate('invoice_date', '<=', $request->date('to')))
            ->when($request->filled('vendor_type'), fn($x) => $x->where('vendor_type', $request->vendor_type))
            ->when($request->filled('invoice_type'), fn($x) => $x->where('invoice_type', $request->invoice_type))
            ->when($request->filled('supplier'), fn($x) => $x->where('supplier_name', 'like', '%' . $request->supplier . '%'))
            ->when($request->filled('itc_eligibility'), fn($x) => $x->where('itc_eligibility', $request->itc_eligibility))
            ->orderByDesc('invoice_date');

        $invoices = $q->paginate(15)->withQueryString();

        return view('purchase_invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('purchase_invoices.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'invoice_no' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'hsn' => 'nullable|string|max:20',

            'supplier_name' => 'nullable|string|max:190',
            'supplier_gstin' => 'nullable|string|max:15',

            'vendor_type' => 'required|in:registered,unregistered,sez,import',
            'invoice_type' => 'required|in:b2b,import,sez,exempted',
            'reverse_charge' => 'nullable|in:yes,no',

            'origin_state' => 'required|string|max:100',
            'place_of_supply' => 'nullable|string|max:100',
            'supply_type' => 'required|in:intra,inter',

            'boe_no' => 'nullable|string|max:50',
            'boe_date' => 'nullable|date',

            'qty' => 'required|integer|min:1',
            'uom' => 'nullable|string|max:20',
            'unit_price' => 'nullable|numeric|min:0',
            'tax_inclusive' => 'nullable|in:yes,no',
            'taxable_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'round_off' => 'nullable|numeric',

            'cgst_rate' => 'nullable|numeric',
            'sgst_rate' => 'nullable|numeric',
            'igst_rate' => 'nullable|numeric',
            'cgst_amount' => 'nullable|numeric',
            'sgst_amount' => 'nullable|numeric',
            'igst_amount' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_invoice_value' => 'nullable|numeric',

            'itc_eligibility' => 'required|in:eligible,ineligible,blocked',
            'itc_type' => 'nullable|in:inputs,capital_goods,input_services',
            'itc_avail_month' => 'nullable|date_format:Y-m',
            'itc_reason' => 'nullable|string|max:255',
        ]);

        // Normalize booleans
        $data['reverse_charge'] = ($r->input('reverse_charge','no') === 'yes');
        $data['tax_inclusive']  = ($r->input('tax_inclusive','no') === 'yes');

        // Server-side recompute split (trust server, not UI)
        $rate = (float)$data['tax_rate'];
        $tv   = (float)$data['taxable_value'];

        // Supply type (if states provided)
        if (in_array($data['invoice_type'], ['import','sez']) || in_array($data['vendor_type'], ['import','sez'])) {
            $data['supply_type'] = 'inter';
        } else {
            $o = strtolower($data['origin_state'] ?? '');
            $p = strtolower($data['place_of_supply'] ?? '');
            if ($o && $p) $data['supply_type'] = ($o === $p) ? 'intra' : 'inter';
        }

        if ($data['supply_type'] === 'intra') {
            $data['cgst_rate'] = $rate/2; $data['sgst_rate'] = $rate/2; $data['igst_rate'] = 0;
            $data['cgst_amount'] = round($tv * ($data['cgst_rate']/100), 2);
            $data['sgst_amount'] = round($tv * ($data['sgst_rate']/100), 2);
            $data['igst_amount'] = 0.00;
        } else {
            $data['cgst_rate'] = 0; $data['sgst_rate'] = 0; $data['igst_rate'] = $rate;
            $data['cgst_amount'] = 0.00; $data['sgst_amount'] = 0.00;
            $data['igst_amount'] = round($tv * ($data['igst_rate']/100), 2);
        }
        $data['tax_amount'] = round(($data['cgst_amount'] + $data['sgst_amount'] + $data['igst_amount']), 2);
        $data['total_invoice_value'] = round($tv + $data['tax_amount'] + (float)($data['round_off'] ?? 0), 2);

        // itc_avail_month: accept "YYYY-MM"
        if ($r->filled('itc_avail_month')) {
            $data['itc_avail_month'] = \Carbon\Carbon::parse($r->input('itc_avail_month').'-01')->startOfMonth();
        }

        $p = PurchaseInvoice::create($data);

        // Classify for 3B bucket and persist
        $bucket = \App\Support\Gst3BClassifier::classify($p);
        $p->update([
            'itc_bucket_code'  => $bucket['code'],
            'itc_bucket_label' => $bucket['label'],
        ]);

        return redirect()->route('purchases.invoices.index')->with('ok', 'Purchase bill saved.');
    }

    public function show(PurchaseInvoice $purchaseInvoice)
    {
        return view('purchase_invoices.show', compact('purchaseInvoice'));
    }

    public function edit(PurchaseInvoice $purchaseInvoice)
    {
        return view('purchase_invoices.edit', compact('purchaseInvoice'));
    }

    public function update(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        $data = $request->validate([
            'invoice_no' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'hsn' => 'nullable|string|max:20',

            'supplier_name' => 'nullable|string|max:190',
            'supplier_gstin' => 'nullable|string|max:15',

            'vendor_type' => 'required|in:registered,unregistered,sez,import',
            'invoice_type' => 'required|in:b2b,import,sez,exempted',
            'reverse_charge' => 'nullable|in:yes,no',

            'origin_state' => 'required|string|max:100',
            'place_of_supply' => 'nullable|string|max:100',
            'supply_type' => 'required|in:intra,inter',

            'boe_no' => 'nullable|string|max:50',
            'boe_date' => 'nullable|date',

            'qty' => 'required|integer|min:1',
            'uom' => 'nullable|string|max:10',
            'unit_price' => 'required|numeric|min:0',
            'tax_inclusive' => 'required|in:yes,no',

            'taxable_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'round_off' => 'nullable|numeric',

            'itc_eligibility' => 'required|in:eligible,ineligible,blocked',
            'itc_avail_month' => 'nullable|date',
        ]);

        // Process the data similar to store method
        $data['place_of_supply'] = $data['place_of_supply'] ?: $data['origin_state'];
        $data['reverse_charge'] = ($data['reverse_charge'] ?? 'no') === 'yes';
        $data['tax_inclusive'] = ($data['tax_inclusive'] ?? 'no') === 'yes';

        $taxCalculation = $this->calculateTaxAmounts(
            $data['taxable_value'],
            $data['tax_rate'],
            $data['supply_type'],
            $data['qty'],
            $data['unit_price'],
            $data['tax_inclusive'],
            $data['round_off'] ?? 0
        );

        $data = array_merge($data, $taxCalculation);

        $purchaseInvoice->update($data);

        // Update classification
        $bucket = \App\Support\Gst3BClassifier::classify($purchaseInvoice->fresh());
        $purchaseInvoice->update([
            'itc_bucket_code'  => $bucket['code'],
            'itc_bucket_label' => $bucket['label'],
        ]);

        return redirect()->route('purchases.invoices.index')->with('ok', 'Purchase bill updated successfully.');
    }

    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->delete();

        return redirect()->route('purchases.invoices.index')->with('ok', 'Purchase bill deleted successfully.');
    }

    /**
     * Calculate all tax amounts and totals based on the given parameters.
     */
    private function calculateTaxAmounts($taxableValue, $taxRate, $supplyType, $qty = 1, $unitPrice = 0, $taxInclusive = false, $roundOff = 0): array
    {
        $qty = max(1, (int) $qty);
        $taxRate = max(0, (float) $taxRate);
        $roundOff = (float) $roundOff;

        // If unit price is provided and tax inclusive, recalculate taxable value
        if ($unitPrice > 0 && $taxInclusive) {
            $grossAmount = $qty * $unitPrice;
            $taxableValue = $grossAmount / (1 + ($taxRate / 100));
        } elseif ($unitPrice > 0 && !$taxInclusive) {
            $taxableValue = $qty * $unitPrice;
        }

        $taxableValue = round($taxableValue, 2);

        // Calculate tax breakdown based on supply type
        if ($supplyType === 'inter') {
            // Inter-state: IGST only
            $cgstRate = 0;
            $sgstRate = 0;
            $igstRate = $taxRate;
            
            $cgstAmount = 0;
            $sgstAmount = 0;
            $igstAmount = round($taxableValue * ($igstRate / 100), 2);
        } else {
            // Intra-state: CGST + SGST split
            $cgstRate = $taxRate / 2;
            $sgstRate = $taxRate / 2;
            $igstRate = 0;
            
            $cgstAmount = round($taxableValue * ($cgstRate / 100), 2);
            $sgstAmount = round($taxableValue * ($sgstRate / 100), 2);
            $igstAmount = 0;
        }

        $totalTax = $cgstAmount + $sgstAmount + $igstAmount;
        $totalInvoiceValue = $taxableValue + $totalTax + $roundOff;

        return [
            'taxable_value' => $taxableValue,
            'cgst_rate' => round($cgstRate, 2),
            'sgst_rate' => round($sgstRate, 2),
            'igst_rate' => round($igstRate, 2),
            'tax_amount' => round($totalTax, 2),
            'cgst_amount' => $cgstAmount,
            'sgst_amount' => $sgstAmount,
            'igst_amount' => $igstAmount,
            'total_invoice_value' => round($totalInvoiceValue, 2),
        ];
    }

    /**
     * Legacy method - keeping for backward compatibility
     */
    private function computeGst($taxable, $rate, $origin, $pos): array
    {
        $tax = round($taxable * ($rate / 100), 2);
        if ($origin && $pos && strcasecmp(trim($origin), trim($pos)) !== 0) {
            // Inter-state → IGST
            return [0, 0, $tax];
        }
        // Intra-state → CGST + SGST split
        $half = round($tax / 2, 2);
        return [$half, $half, 0];
    }
}
