<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['user_id','date','category','ref_no','amount','itc_claimed','meta'];
    protected $casts = ['date'=>'date','amount'=>'decimal:2','itc_claimed'=>'boolean','meta'=>'array'];
}
