<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseInvoiceRequest extends FormRequest
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
            'invoice_no'      => 'required|string|max:50|unique:purchase_invoices,invoice_no',
            'invoice_date'    => 'required|date|before_or_equal:today',
            'hsn'             => 'nullable|string|max:20',
            
            // Supplier information
            'supplier_name'   => 'nullable|string|max:190',
            'supplier_gstin'  => [
                'nullable',
                'string',
                'max:15',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                'required_if:vendor_type,registered,sez'
            ],
            
            // Classification
            'vendor_type'     => 'required|in:registered,unregistered,sez,import',
            'invoice_type'    => 'required|in:b2b,import,sez,exempted',
            'reverse_charge'  => 'required|in:yes,no',
            
            // Location and supply
            'origin_state'    => 'required|string|max:100',
            'place_of_supply' => 'nullable|string|max:100',
            'supply_type'     => 'required|in:intra,inter',
            
            // Import fields
            'boe_no'          => 'nullable|string|max:50|required_if:vendor_type,import',
            'boe_date'        => 'nullable|date|required_if:vendor_type,import',
            
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
            
            // Final totals
            'round_off'       => 'nullable|numeric|between:-999.99,999.99',
            'total_invoice_value' => 'nullable|numeric|min:0|max:999999999.99',
            
            // ITC fields
            'itc_eligibility' => 'required|in:eligible,ineligible,blocked',
            'itc_type'        => 'nullable|in:inputs,capital_goods,input_services',
            'itc_avail_month' => 'nullable|date_format:Y-m',
            'itc_reason'      => 'nullable|string|max:255',
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
            'supplier_gstin.required_if' => 'Supplier GSTIN is required for registered/SEZ vendors.',
            'supplier_gstin.regex' => 'Please enter a valid GSTIN format (e.g., 22AAAAA0000A1Z5).',
            'boe_no.required_if' => 'Bill of Entry number is required for import invoices.',
            'boe_date.required_if' => 'Bill of Entry date is required for import invoices.',
            'qty.max' => 'Quantity cannot exceed 999,999 units.',
            'tax_rate.max' => 'Tax rate cannot exceed 100%.',
            'taxable_value.max' => 'Taxable value is too large.',
            'unit_price.max' => 'Unit price is too large.',
            'total_invoice_value.max' => 'Total invoice value is too large.',
            'round_off.between' => 'Round off must be between -999.99 and 999.99.',
            'itc_avail_month.date_format' => 'ITC availment month must be in YYYY-MM format.',
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
            'supplier_name' => 'supplier name',
            'supplier_gstin' => 'supplier GSTIN',
            'hsn' => 'HSN code',
            'vendor_type' => 'vendor type',
            'invoice_type' => 'invoice type',
            'reverse_charge' => 'reverse charge',
            'origin_state' => 'origin state',
            'place_of_supply' => 'place of supply',
            'supply_type' => 'supply type',
            'boe_no' => 'Bill of Entry number',
            'boe_date' => 'Bill of Entry date',
            'qty' => 'quantity',
            'uom' => 'unit of measure',
            'unit_price' => 'unit price',
            'tax_inclusive' => 'tax inclusive option',
            'taxable_value' => 'taxable value',
            'tax_rate' => 'tax rate',
            'round_off' => 'round off',
            'total_invoice_value' => 'total invoice value',
            'itc_eligibility' => 'ITC eligibility',
            'itc_type' => 'ITC type',
            'itc_avail_month' => 'ITC availment month',
            'itc_reason' => 'ITC reason',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            
            // Custom validation for import invoices
            if ($data['vendor_type'] === 'import') {
                if (!isset($data['boe_no']) || empty($data['boe_no'])) {
                    $validator->errors()->add('boe_no', 'Bill of Entry number is required for import invoices.');
                }
                if (!isset($data['boe_date']) || empty($data['boe_date'])) {
                    $validator->errors()->add('boe_date', 'Bill of Entry date is required for import invoices.');
                }
            }
            
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert reverse_charge from yes/no to boolean
        if ($this->has('reverse_charge')) {
            $this->merge([
                'reverse_charge' => $this->reverse_charge === 'yes'
            ]);
        }

        // Convert tax_inclusive from yes/no to boolean
        if ($this->has('tax_inclusive')) {
            $this->merge([
                'tax_inclusive' => $this->tax_inclusive === 'yes'
            ]);
        }

        // Convert ITC availment month to proper date format
        if ($this->has('itc_avail_month') && !empty($this->itc_avail_month)) {
            $this->merge([
                'itc_avail_month' => $this->itc_avail_month . '-01'
            ]);
        }
    }
}
