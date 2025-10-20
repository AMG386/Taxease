<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = ['user_id','title','message','due_at','channel','is_sent'];
    protected $casts = ['due_at'=>'datetime','is_sent'=>'boolean'];
}
