<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id','description','hsn_sac','qty','rate','taxable_value',
        'gst_rate','cgst_rate','sgst_rate','igst_rate','cgst','sgst','igst',
        'is_nil','is_exempt','is_non_gst'
    ];

    protected $casts = [
        'qty'=>'decimal:3','rate'=>'decimal:2','taxable_value'=>'decimal:2',
        'gst_rate'=>'decimal:2','cgst_rate'=>'decimal:2','sgst_rate'=>'decimal:2','igst_rate'=>'decimal:2',
        'cgst'=>'decimal:2','sgst'=>'decimal:2','igst'=>'decimal:2',
        'is_nil'=>'boolean','is_exempt'=>'boolean','is_non_gst'=>'boolean'
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
