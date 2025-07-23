<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'req_number',
        'req_title',
        'req_division',
        'req_ref',
        'req_added_by',
        'req_date_added',
        'req_status',
        'req_hod_id',
    ];

    // Example relationship: a requisition has many items
    public function items()
    {
        return $this->hasMany(RequisitionItem::class, 'ri_ref', 'req_ref');
    }

    // Example relationship: a requisition belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'req_added_by');
    }
}
