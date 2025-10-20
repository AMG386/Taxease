<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GstReturn extends Model
{
    // If your table/PK are standard, you can omit these.
    // protected $table = 'gst_returns';
    // protected $primaryKey = 'id';
    // public $timestamps = true;

    protected $fillable = [
        'user_id','type','period_from','period_to','status',
        'taxable_value','cgst','sgst','igst','cess','total_tax',
        'itc_eligible','net_payable','meta','prepared_on','filed_on'
    ];

    protected $casts = [
        'period_from'   => 'date',
        'period_to'     => 'date',
        'prepared_on'   => 'datetime',
        'filed_on'      => 'datetime',
        'meta'          => 'array',
        'taxable_value' => 'decimal:2',
        'cgst'          => 'decimal:2',
        'sgst'          => 'decimal:2',
        'igst'          => 'decimal:2',
        'cess'          => 'decimal:2',
        'total_tax'     => 'decimal:2',
        'itc_eligible'  => 'decimal:2',
        'net_payable'   => 'decimal:2',
    ];

    protected $appends = ['total_tax_amount','itc_amount','net_payable_amount'];

    public function items(): HasMany
    {
        return $this->hasMany(GstReturnItem::class)
            ->orderBy('section')
            ->orderBy('invoice_date');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeOfType($q, string $type)
    {
        return $q->where('type', $type);
    }

    public function scopeOfStatus($q, string $status)
    {
        return $q->where('status', $status);
    }

    public function scopeForPeriod($q, $from, $to)
    {
        return $q->whereDate('period_from', '>=', $from)
                 ->whereDate('period_to', '<=', $to);
    }

    // Accessors
    public function getTotalTaxAmountAttribute()
    {
        return (float)($this->total_tax ?? 0);
    }

    public function getItcAmountAttribute()
    {
        return (float)($this->itc_eligible ?? 0);
    }

    public function getNetPayableAmountAttribute()
    {
        return is_null($this->net_payable)
            ? max($this->total_tax_amount - $this->itc_amount, 0.0)
            : (float)$this->net_payable;
    }
    
public function audits(): HasMany
{
    return $this->hasMany(\App\Models\GstReturnAudit::class)->latest();
}

}

