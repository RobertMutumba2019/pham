<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectedCopyMaster extends Model
{
    protected $fillable = [
        'rcm_comment',
        'rcm_date_added',
        'rcm_added_by',
        'rcm_rejected_by',
        'rcm_type_id',
        'rcm_type',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'rcm_added_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rcm_rejected_by');
    }

    // If rcm_type_id refers to a requisition
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'rcm_type_id');
    }
}
