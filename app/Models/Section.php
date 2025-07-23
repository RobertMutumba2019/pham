<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section_name',
        'section_dept_id',
        'section_added_by',
        'section_date_added',
        'section_status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'section_dept_id');
    }
}
