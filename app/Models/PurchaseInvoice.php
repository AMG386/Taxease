<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        // Core invoice fields
        'invoice_no',
        'invoice_date', 
        'hsn',
        
        // Supplier information
        'supplier_name',
        'supplier_gstin',
        
        // Classification fields
        'vendor_type',
        'invoice_type',
        'reverse_charge',
        
        // Location and supply
        'origin_state',
        'place_of_supply', 
        'supply_type',
        
        // Import fields
        'boe_no',
        'boe_date',
        
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
        
        // Final totals
        'round_off',
        'total_invoice_value',
        
        // ITC fields
        'itc_eligibility',
        'itc_type',
        'itc_avail_month',
        'itc_reason',
        'itc_bucket_code',
        'itc_bucket_label',
        
        // Legacy field (keeping for compatibility)
        'type'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'boe_date' => 'date',
        'itc_avail_month' => 'date',
        'qty' => 'integer',
        'reverse_charge' => 'boolean',
        'tax_inclusive' => 'boolean',
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
    ];

    /**
     * Get the formatted invoice date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->invoice_date->format('d/m/Y');
    }

    /**
     * Get the formatted BOE date.
     */
    public function getFormattedBoeDateAttribute()
    {
        return $this->boe_date ? $this->boe_date->format('d/m/Y') : null;
    }

    /**
     * Check if this is an inter-state supply.
     */
    public function isInterState()
    {
        return $this->supply_type === 'inter';
    }

    /**
     * Check if this is a registered vendor.
     */
    public function isRegisteredVendor()
    {
        return $this->vendor_type === 'registered';
    }

    /**
     * Check if this is an import invoice.
     */
    public function isImport()
    {
        return $this->invoice_type === 'import' || $this->vendor_type === 'import';
    }

    /**
     * Check if ITC is eligible.
     */
    public function isItcEligible()
    {
        return $this->itc_eligibility === 'eligible';
    }

    /**
     * Get the total tax amount (sum of all tax components).
     */
    public function getTotalTaxAttribute()
    {
        return $this->cgst_amount + $this->sgst_amount + $this->igst_amount;
    }

    /**
     * Get the ITC availment month in readable format.
     */
    public function getItcAvailMonthFormattedAttribute()
    {
        return $this->itc_avail_month ? $this->itc_avail_month->format('M Y') : null;
    }

    /**
     * Scope for filtering by vendor type.
     */
    public function scopeByVendorType($query, $vendorType)
    {
        return $query->where('vendor_type', $vendorType);
    }

    /**
     * Scope for filtering by ITC eligibility.
     */
    public function scopeByItcEligibility($query, $eligibility)
    {
        return $query->where('itc_eligibility', $eligibility);
    }

    /**
     * Scope for filtering by ITC bucket.
     */
    public function scopeByItcBucket($query, $bucketCode)
    {
        return $query->where('itc_bucket_code', $bucketCode);
    }

    /**
     * Get the GSTR-3B classification for this invoice.
     */
    public function getGst3BClassification(): array
    {
        return \App\Support\Gst3BClassifier::classify($this);
    }
}
