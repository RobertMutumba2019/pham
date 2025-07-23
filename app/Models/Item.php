<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item_code',
        'item_name',
        'item_unit_of_measure',
        'item_added_by',
        'item_date_added',
    ];
}
