<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = [
        'ur_name',
        'ur_added_by',
        'ur_date_added',
    ];

    public function accessRights()
    {
        return $this->hasMany(AccessRight::class, 'ar_role_id');
    }
}
