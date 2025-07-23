<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalOrder extends Model
{
    protected $fillable = [
        'app_role_id',
    ];

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'app_role_id');
    }
}
