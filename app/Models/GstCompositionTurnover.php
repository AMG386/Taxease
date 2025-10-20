<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstCompositionTurnover extends Model
{
    protected $fillable = [
        'user_id','period_from','period_to','total_turnover','tax_rate','tax_amount','status','meta'
    ];

    protected $casts = [
        'period_from' => 'date',
        'period_to'   => 'date',
        'meta'        => 'array',
    ];
}
