<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hod extends Model
{
    protected $fillable = [
        'hod_dept_id',
        'hod_user_id',
        'hod_added_by',
        'hod_date_added',
    ];
}
