<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GstProfile extends Model
{
    protected $fillable = [
        'user_id',
        'firm_name',
        'trade_name',
        'address_line1',
        'address_line2',
        'pincode',
        'state',
        'city',
        'gstin',
        'gst_type',
        'business_type',
        'filing_frequency',
        'default_gst_rate',
        'composition_rate',
        'meta'
    ];
    
    protected $casts = [
        'meta' => 'array',
        'composition_rate' => 'decimal:2',
        'default_gst_rate' => 'decimal:2'
    ];

    /**
     * Get the user that owns the GST profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
