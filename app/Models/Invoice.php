<?php

namespace App\Models;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    protected $fillable = [
        'user_id','type','invoice_no','date','gstin','place_of_supply','customer_state','supplier_state',
        'taxable_amount','cgst','sgst','igst','total_amount','doc_type','counterparty_gstin','counterparty_name',
        'is_export','with_lut','is_rcm'
    ];

    protected $casts = [
        'date' => 'date',
        'taxable_amount' => 'decimal:2',
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
        'igst' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_export' => 'boolean',
        'with_lut' => 'boolean',
        'is_rcm' => 'boolean',
    ];

    public function scopePeriod(Builder $q, string $period): Builder
    {
        [$y,$m] = explode('-', $period);
        return $q->whereBetween('date', [
            "{$y}-{$m}-01",
            date('Y-m-t', strtotime("{$y}-{$m}-01")),
        ]);
    }

    public function scopeType(Builder $q, string $type): Builder
    {
        return $q->where('type',$type);
    }

    public function items(){
        return $this->hasMany(InvoiceItem::class);
    }
}
