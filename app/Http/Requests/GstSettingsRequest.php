<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GstSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users to update their GST settings
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Firm details
            'firm_name' => 'nullable|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'address_line1' => 'nullable|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'pincode' => 'nullable|string|size:6|regex:/^[0-9]{6}$/',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            
            // GST details
            'gstin' => 'nullable|string|max:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'gst_type' => 'required|in:regular,composition',
            'business_type' => 'nullable|in:manufacturer,trader,restaurant,service',
            'filing_frequency' => 'nullable|in:monthly,qrmp,cmp_quarterly,cmp_annual',
            
            // Rates
            'default_gst_rate' => 'nullable|numeric|min:0|max:99.99',
            'composition_rate' => 'nullable|numeric|min:0|max:99.99',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'gstin.regex' => 'Please enter a valid GSTIN format (e.g., 22AAAAA0000A1Z5)',
            'pincode.regex' => 'Pincode must be exactly 6 digits',
            'pincode.size' => 'Pincode must be exactly 6 digits',
            'gst_type.required' => 'Please select a GST type',
            'gst_type.in' => 'GST type must be either Regular or Composition',
            'business_type.in' => 'Please select a valid business type',
            'filing_frequency.in' => 'Please select a valid filing frequency',
            'default_gst_rate.numeric' => 'Default GST rate must be a valid number',
            'default_gst_rate.max' => 'Default GST rate cannot exceed 99.99%',
            'composition_rate.numeric' => 'Composition rate must be a valid number',
            'composition_rate.max' => 'Composition rate cannot exceed 99.99%',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'firm_name' => 'firm name',
            'trade_name' => 'trade name',
            'address_line1' => 'address line 1',
            'address_line2' => 'address line 2',
            'gstin' => 'GSTIN',
            'gst_type' => 'GST type',
            'business_type' => 'business type',
            'filing_frequency' => 'filing frequency',
            'default_gst_rate' => 'default GST rate',
            'composition_rate' => 'composition rate',
        ];
    }
}
