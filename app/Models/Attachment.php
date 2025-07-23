<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'at_req_id',
        'at_file_path',
        'at_uploaded_by',
        'at_date_uploaded',
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'at_req_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'at_uploaded_by');
    }
}
