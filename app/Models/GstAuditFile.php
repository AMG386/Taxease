<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GstAuditFile extends Model
{
    protected $fillable = ['gst_return_id','filename','disk','path','remarks'];
}