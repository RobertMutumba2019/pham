<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select('id', 'user_name', 'user_surname', 'user_othername', 'user_email', 'user_role', 'user_active', 'user_last_logged_in')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Username',
            'Surname',
            'Other Name',
            'Email',
            'Role',
            'Active',
            'Last Logged In',
        ];
    }
} 

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select('id', 'user_name', 'user_surname', 'user_othername', 'user_email', 'user_role', 'user_active', 'user_last_logged_in')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Username',
            'Surname',
            'Other Name',
            'Email',
            'Role',
            'Active',
            'Last Logged In',
        ];
    }
} 