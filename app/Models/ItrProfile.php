<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItrProfile extends Model
{
    protected $fillable = ['user_id','pan','full_name','assessment_year','meta'];
    protected $casts = ['meta'=>'array'];
}
