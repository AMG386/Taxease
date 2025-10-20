<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstReturnItem extends Model
{
    protected $fillable = [
        'gst_return_id','section','invoice_no','invoice_date','counterparty_gstin',
        'party_name','hsn','qty','uom','taxable_value','cgst','sgst','igst','cess','total',
        'itc_breakup','raw'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'itc_breakup'  => 'array',
        'raw'          => 'array',
    ];
}
