<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    protected $fillable = [
        'requisition_id',
        'item_description',
        'item_quantity',
        'item_unit',
        'item_estimated_cost',
        // Keep old fields for backward compatibility
        'ri_code',
        'ri_quantity',
        'ri_uom',
        'ri_description',
        'ri_ref',
    ];

    protected $casts = [
        'item_estimated_cost' => 'decimal:2',
        'item_quantity' => 'integer',
    ];

    // Relationship: a requisition item belongs to a requisition
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
