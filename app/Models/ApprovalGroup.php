<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalGroup extends Model
{
    protected $fillable = [
        'apg_name',
        'apg_user',
        'apg_added_by',
        'apg_date_added',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'apg_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'apg_user');
    }
}
