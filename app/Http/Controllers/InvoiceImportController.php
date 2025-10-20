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
        // Always return JSON (prevents 302 “back” redirect on validation fail)
        if (! $request->headers->has('Accept')) {
            $request->headers->set('Accept', 'application/json');
        }

        $validator = Validator::make($request->all(), [
            'type'  => 'required|in:sales,purchase',
            'items' => 'required|array|min:1',
            'items.*.invoice_no'   => 'required|string|max:100',
            'items.*.invoice_date' => 'nullable|string', // we normalize below
            'items.*.hsn'          => 'nullable|string|max:50',
            'items.*.qty'          => 'nullable|numeric|min:0',
            'items.*.uom'          => 'nullable|string|max:10',
            'items.*.rate'         => 'nullable|numeric|min:0',
            'items.*.tax_rate'     => 'nullable|numeric|min:0',
            // add any other expected fields here…
        ], [
            'items.required' => 'No items received for import.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'     => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type  = $request->input('type');
        $items = $request->input('items');

        $ok = 0;
        $errors = [];
        $created_ids = [];

        DB::beginTransaction();
        try {
            foreach ($items as $rowIndex => $item) {
                // Normalize / map incoming payload to your table schema
                $payload = [
                    'invoice_no'   => trim((string)($item['invoice_no'] ?? '')),
                    'invoice_date' => $this->normalizeDate($item['invoice_date'] ?? null),
                    'hsn'          => trim((string)($item['hsn'] ?? '')),
                    'qty'          => $this->toNumber($item['qty'] ?? 0),
                    'uom'          => trim((string)($item['uom'] ?? '')),
                    'rate'         => $this->toNumber($item['rate'] ?? 0),
                    'tax_rate'     => $this->toNumber($item['tax_rate'] ?? 0),
                    // Add any columns you actually have in your migrations/models:
                    // 'customer_id' => $item['customer_id'] ?? null,
                    // 'vendor_id'   => $item['vendor_id'] ?? null,
                    // 'total'       => $this->toNumber($item['total'] ?? 0),
                ];

                // Per-row validation (optional but helpful)
                $rowValidator = Validator::make($payload, [
                    'invoice_no'   => 'required|string|max:100',
                    'invoice_date' => 'nullable|date',
                    'qty'          => 'nullable|numeric',
                    'rate'         => 'nullable|numeric',
                    'tax_rate'     => 'nullable|numeric',
                ]);

                if ($rowValidator->fails()) {
                    $errors[] = [
                        'row'    => $rowIndex + 1,
                        'fields' => $rowValidator->errors(),
                    ];
                    continue;
                }

                // Upsert / Insert
                if ($type === 'sales') {
                    $model = SalesInvoice::updateOrCreate(
                        ['invoice_no' => $payload['invoice_no']],
                        $payload
                    );
                } else { // 'purchase'
                    $model = PurchaseInvoice::updateOrCreate(
                        ['invoice_no' => $payload['invoice_no']],
                        $payload
                    );
                }

                $created_ids[] = $model->id;
                $ok++;
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'ok'     => false,
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }

        return response()->json([
            'ok'          => true,
            'type'        => $type,
            'imported'    => $ok,
            'failed'      => count($errors),
            'errors'      => $errors,     // per-row errors with row numbers
            'created_ids' => $created_ids // for quick debugging
        ], $ok > 0 ? 200 : 422);
    }

    private function normalizeDate($value)
    {
        // Accepts: "2025-10-01", "01/10/2025", "01-10-2025", "1 Oct 2025"
        if (empty($value)) return null;

        $value = trim((string)$value);

        // Try common dd/mm/yyyy
        if (preg_match('~^\d{1,2}/\d{1,2}/\d{4}$~', $value)) {
            [$d, $m, $y] = explode('/', $value);
            return sprintf('%04d-%02d-%02d', $y, $m, $d);
        }

        // Try dd-mm-yyyy
        if (preg_match('~^\d{1,2}-\d{1,2}-\d{4}$~', $value)) {
            [$d, $m, $y] = explode('-', $value);
            return sprintf('%04d-%02d-%02d', $y, $m, $d);
        }

        // Let strtotime handle other formats (e.g., 2025-10-01, 1 Oct 2025)
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function toNumber($v)
    {
        // Handle "1,234.56" or "1 234,56" etc.
        if (is_string($v)) {
            $v = str_replace([' ', ','], ['', ''], $v);
        }
        return is_numeric($v) ? $v + 0 : 0;
    }
}
