<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['user_id','date','head','sub_head','ref_no','amount','meta'];
    protected $casts = ['date'=>'date','amount'=>'decimal:2','meta'=>'array'];
}
