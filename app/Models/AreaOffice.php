<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaOffice extends Model
{
    protected $fillable = [
        'area_office_name',
        'area_office_district_code_id',
        'area_office_territory_id',
        'area_office_cost_center',
        'area_office_added_by',
        'area_office_date_added',
        'area_office_status',
    ];
}
