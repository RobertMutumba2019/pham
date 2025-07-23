<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'dept_name',
        'dept_added_by',
        'dept_date_added',
        'dept_office_id',
        'dept_status',
    ];

    // Example relationship: a department has many users (if user_department_id exists)
    public function users()
    {
        return $this->hasMany(User::class, 'user_department_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'section_dept_id');
    }
}
