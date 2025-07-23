<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrailOfUser extends Model
{
    protected $fillable = [
        'trail_sql',
        'trail_date',
        'trail_user',
        'trail_page',
        'trail_browser',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'trail_user');
    }
}
