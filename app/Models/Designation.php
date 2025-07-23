<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'designation_name',
        'designation_added_by',
        'designation_date_added',
    ];
}
