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
}
