<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstFiling extends Model
{
    protected $fillable = ['user_id','filing_type','period','payload','status','filed_at','total_payable'];
    protected $casts = ['payload' => 'array', 'filed_at' => 'datetime', 'total_payable' => 'decimal:2'];
}
