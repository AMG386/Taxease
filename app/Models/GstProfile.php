<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GstProfile extends Model
{
    protected $fillable = ['user_id','gstin','gst_type','business_type','composition_rate','meta'];
    protected $casts = ['meta'=>'array','composition_rate'=>'decimal:2'];
}
