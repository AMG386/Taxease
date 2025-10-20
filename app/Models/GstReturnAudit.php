<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GstReturnAudit extends Model
{
    protected $fillable = [
        'gst_return_id','uploaded_by','file_path','original_name','size','mime','remarks'
    ];

    public function gstReturn(): BelongsTo
    {
        return $this->belongsTo(GstReturn::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
