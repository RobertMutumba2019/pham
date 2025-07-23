<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{
    protected $fillable = [
        'ar_role_id',
        'ar_page',
        'ar_a',
        'ar_v',
        'ar_e',
        'ar_d',
        'ar_p',
        'ar_i',
        'ar_x',
    ];

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'ar_role_id');
    }
}
