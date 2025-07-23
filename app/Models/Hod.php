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

    public function department()
    {
        return $this->belongsTo(Department::class, 'hod_dept_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'hod_user_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'hod_added_by');
    }
}
