<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'district_name',
        'district_code',
        'district_added_by',
        'district_date_added',
    ];
}
