<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalMatrix extends Model
{
    protected $fillable = [
        'ap_code',
        'ap_unit_code',
        'ap_added_by',
        'ap_date_added',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'gr_matrix', 'id');
    }
}
