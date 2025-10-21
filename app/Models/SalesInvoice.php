<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        // Core invoice fields
        'invoice_no',
        'invoice_date',
        'customer_name',
        'customer_gstin',
        'hsn',
        
        // Quantity and pricing
        'qty',
        'uom',
        'unit_price',
        'tax_inclusive',
        
        // Tax calculation
        'taxable_value',
        'tax_rate',
        'cgst_rate',
        'sgst_rate', 
        'igst_rate',
        'tax_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        
        // Location and classification
        'place_of_supply',
        'origin_state',
        'invoice_type',
        'supply_type',
        'reverse_charge',
        
        // Final totals
        'round_off',
        'total_invoice_value',
        
        // Legacy field (keeping for compatibility)
        'type'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'taxable_value' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'igst_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
        'total_invoice_value' => 'decimal:2',
        'reverse_charge' => 'boolean',
        'tax_inclusive' => 'boolean',
    ];

    /**
     * Get the formatted invoice date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->invoice_date->format('d/m/Y');
    }

    /**
     * Check if this is an inter-state supply.
     */
    public function isInterState()
    {
        return $this->supply_type === 'inter';
    }

    /**
     * Check if this is a B2B invoice.
     */
    public function isB2B()
    {
        return $this->invoice_type === 'b2b';
    }

    /**
     * Get the total tax amount (sum of all tax components).
     */
    public function getTotalTaxAttribute()
    {
        return $this->cgst_amount + $this->sgst_amount + $this->igst_amount;
    }
}
