<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_name',
        'user_surname',
        'user_othername',
        'user_status',
        'user_email',
        'user_telephone',
        'user_gender',
        'user_password',
        'user_date_added',
        'user_added_by',
        'user_role',
        'user_forgot_password',
        'user_active',
        'check_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Example relationship: a user belongs to a department (if user_department_id exists)
    public function department()
    {
        return $this->belongsTo(Department::class, 'user_department_id');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'req_added_by');
    }
}
