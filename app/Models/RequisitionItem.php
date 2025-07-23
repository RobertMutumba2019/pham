<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    protected $fillable = [
        'ri_code',
        'ri_quantity',
        'ri_uom',
        'ri_description',
        'ri_ref',
    ];

    // Example relationship: a requisition item belongs to a requisition
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'ri_ref', 'req_ref');
    }
}
