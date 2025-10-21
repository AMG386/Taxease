<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Http\Requests\SalesInvoiceRequest;

class SalesInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = SalesInvoice::query()
            ->when($request->filled('from'), fn($x) => $x->whereDate('invoice_date', '>=', $request->date('from')))
            ->when($request->filled('to'),   fn($x) => $x->whereDate('invoice_date', '<=', $request->date('to')))
            ->when($request->filled('invoice_type'), fn($x) => $x->where('invoice_type', $request->invoice_type))
            ->when($request->filled('customer'), fn($x) => $x->where('customer_name', 'like', '%' . $request->customer . '%'))
            ->orderByDesc('invoice_date');

        $invoices = $q->paginate(15)->withQueryString();

        return view('sales_invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('sales_invoices.create');
    }

    public function store(SalesInvoiceRequest $request)
    {
        $data = $request->validated();

        // Recalculate tax values to ensure consistency
        $taxCalculation = $this->calculateTaxAmounts(
            $data['taxable_value'],
            $data['tax_rate'],
            $data['supply_type'],
            $data['qty'] ?? 1,
            $data['unit_price'] ?? 0,
            $data['tax_inclusive'] === 'yes',
            $data['round_off'] ?? 0
        );

        // Override form values with calculated values for consistency
        $data = array_merge($data, $taxCalculation);

        SalesInvoice::create($data);

        return redirect()->route('sales.invoices.index')->with('status', 'Sales invoice created successfully.');
    }

    public function show(SalesInvoice $salesInvoice)
    {
        return view('sales_invoices.show', compact('salesInvoice'));
    }

    public function edit(SalesInvoice $salesInvoice)
    {
        return view('sales_invoices.edit', compact('salesInvoice'));
    }

    public function update(SalesInvoiceRequest $request, SalesInvoice $salesInvoice)
    {
        $data = $request->validated();

        // Recalculate tax values to ensure consistency
        $taxCalculation = $this->calculateTaxAmounts(
            $data['taxable_value'],
            $data['tax_rate'],
            $data['supply_type'],
            $data['qty'] ?? 1,
            $data['unit_price'] ?? 0,
            $data['tax_inclusive'] === 'yes',
            $data['round_off'] ?? 0
        );

        // Override form values with calculated values for consistency
        $data = array_merge($data, $taxCalculation);

        $salesInvoice->update($data);

        return redirect()->route('sales.invoices.index')->with('status', 'Sales invoice updated successfully.');
    }

    public function destroy(SalesInvoice $salesInvoice)
    {
        $salesInvoice->delete();

        return redirect()->route('sales.invoices.index')->with('status', 'Sales invoice deleted successfully.');
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
