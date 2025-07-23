<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'gr_name',
        'gr_matrix',
        'gr_added_by',
        'gr_date_added',
    ];
}
