<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Core invoice details
            'invoice_no'      => 'required|string|max:50|unique:sales_invoices,invoice_no',
            'invoice_date'    => 'required|date|before_or_equal:today',
            'customer_name'   => 'nullable|string|max:200',
            'customer_gstin'  => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                'required_if:invoice_type,b2b'
            ],
            'hsn'             => 'nullable|string|max:20',
            
            // Quantity and pricing
            'qty'             => 'required|integer|min:1|max:999999',
            'uom'             => 'nullable|string|max:20',
            'unit_price'      => 'nullable|numeric|min:0|max:999999999.99',
            'tax_inclusive'   => 'required|in:yes,no',
            
            // Tax calculation
            'taxable_value'   => 'required|numeric|min:0|max:999999999.99',
            'tax_rate'        => 'required|numeric|min:0|max:100',
            'cgst_rate'       => 'nullable|numeric|min:0|max:100',
            'sgst_rate'       => 'nullable|numeric|min:0|max:100',
            'igst_rate'       => 'nullable|numeric|min:0|max:100',
            'tax_amount'      => 'nullable|numeric|min:0',
            'cgst_amount'     => 'nullable|numeric|min:0',
            'sgst_amount'     => 'nullable|numeric|min:0',
            'igst_amount'     => 'nullable|numeric|min:0',
            
            // Location and classification
            'place_of_supply' => 'nullable|string|max:100',
            'origin_state'    => 'required|string|max:100',
            'invoice_type'    => 'required|in:b2b,b2c,export,sez,exempted',
            'supply_type'     => 'required|in:intra,inter',
            'reverse_charge'  => 'required|in:yes,no',
            
            // Final totals
            'round_off'       => 'nullable|numeric|between:-999.99,999.99',
            'total_invoice_value' => 'nullable|numeric|min:0|max:999999999.99',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'invoice_no.unique' => 'This invoice number already exists. Please use a different number.',
            'invoice_date.before_or_equal' => 'Invoice date cannot be in the future.',
            'customer_gstin.required_if' => 'Customer GSTIN is required for B2B invoices.',
            'customer_gstin.regex' => 'Please enter a valid GSTIN format (e.g., 22AAAAA0000A1Z5).',
            'qty.max' => 'Quantity cannot exceed 999,999 units.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            'taxable_value.max' => 'Taxable value is too large.',
            'unit_price.max' => 'Unit price is too large.',
            'total_invoice_value.max' => 'Total invoice value is too large.',
            'round_off.between' => 'Round off must be between -999.99 and 999.99.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'invoice_no' => 'invoice number',
            'invoice_date' => 'invoice date',
            'customer_name' => 'customer name',
            'customer_gstin' => 'customer GSTIN',
            'hsn' => 'HSN code',
            'qty' => 'quantity',
            'uom' => 'unit of measure',
            'unit_price' => 'unit price',
            'tax_inclusive' => 'tax inclusive option',
            'taxable_value' => 'taxable value',
            'tax_rate' => 'tax rate',
            'place_of_supply' => 'place of supply',
            'origin_state' => 'origin state',
            'invoice_type' => 'invoice type',
            'supply_type' => 'supply type',
            'reverse_charge' => 'reverse charge',
            'round_off' => 'round off',
            'total_invoice_value' => 'total invoice value',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation logic can go here
            $data = $this->all();
            
            // Validate that tax amounts are consistent with taxable value and rates
            if (isset($data['taxable_value'], $data['tax_rate'])) {
                $expectedTax = $data['taxable_value'] * ($data['tax_rate'] / 100);
                $actualTax = ($data['cgst_amount'] ?? 0) + ($data['sgst_amount'] ?? 0) + ($data['igst_amount'] ?? 0);
                
                // Allow small rounding differences (within 1 rupee)
                if (abs($expectedTax - $actualTax) > 1) {
                    $validator->errors()->add('tax_amount', 'Tax amounts do not match the calculated tax based on taxable value and rate.');
                }
            }
        });
    }
}
