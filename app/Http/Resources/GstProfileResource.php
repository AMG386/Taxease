<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GstProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            
            // Firm details
            'firm_details' => [
                'firm_name' => $this->firm_name,
                'trade_name' => $this->trade_name,
                'address' => [
                    'line1' => $this->address_line1,
                    'line2' => $this->address_line2,
                    'city' => $this->city,
                    'state' => $this->state,
                    'pincode' => $this->pincode,
                ],
            ],
            
            // GST configuration
            'gst_config' => [
                'gstin' => $this->gstin,
                'gst_type' => $this->gst_type,
                'business_type' => $this->business_type,
                'filing_frequency' => $this->filing_frequency,
            ],
            
            // Tax rates
            'tax_rates' => [
                'default_gst_rate' => $this->default_gst_rate,
                'composition_rate' => $this->composition_rate,
            ],
            
            // Additional metadata
            'meta' => $this->meta,
            
            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
