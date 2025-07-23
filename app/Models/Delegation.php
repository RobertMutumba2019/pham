<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    protected $fillable = [
        'del_to',
        'del_start_date',
        'del_end_date',
        'del_reason',
        'del_added_by',
        'del_date_added',
    ];
}
