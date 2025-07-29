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

    public function role()
    {
        return $this->belongsTo(\App\Models\UserRole::class, 'user_role', 'id'); // Adjust 'id' if your PK is different
    }

    public function hasRight($page, $action)
    {
        $roleId = $this->user_role;
        $rightColumn = 'ar_' . strtolower($action); // e.g., ar_a, ar_v, ar_e, etc.
        $access = \App\Models\AccessRight::where('ar_role_id', $roleId)
            ->where('ar_page', $page)
            ->first();
        if ($access && isset($access[$rightColumn])) {
            return $access[$rightColumn] == 1;
        }
        return false;
    }
}


// PS C:\xampp\htdocs\stor\laravel12app> php artisan tinker
// App\Models\User::create([
//     'user_name' => 'admin',
//     'user_surname' => 'Admin',
//     'user_othername' => 'User',
//     'user_status' => 1,
//     'user_email' => 'admin@example.com',
//     'user_telephone' => '0000000000',
//     'user_gender' => 'M',
//     'user_password' => md5('Admin@2025'),
//     'user_date_added' => now(),
//     'user_added_by' => 1,
//     'user_role' => 1,
//     'user_forgot_password' => 0,
//     'user_active' => 1,
//     'check_number' => 'ADMIN001'
// ]);
