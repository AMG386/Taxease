<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'invoice_no','invoice_date','supplier_name','supplier_gstin','hsn','qty','uom',
        'taxable_value','tax_rate','cgst_amount','sgst_amount','igst_amount',
        'place_of_supply','origin_state','type'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'taxable_value' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'cgst_amount' => 'decimal:2',
        'sgst_amount' => 'decimal:2',
        'igst_amount' => 'decimal:2',
    ];
}
