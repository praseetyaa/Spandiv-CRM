<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin Spandiv (no company)
        User::create([
            'name' => 'Admin Spandiv',
            'email' => 'admin@spandiv.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'company_id' => null,
        ]);

        // Sample Company + Admin
        $company = Company::create([
            'name' => 'PT Contoh Digital',
            'slug' => 'pt-contoh-digital',
            'email' => 'info@contoh.com',
            'phone' => '021-1234567',
            'address' => 'Jakarta Selatan',
        ]);

        User::create([
            'name' => 'Admin Contoh',
            'email' => 'admin@contoh.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'company_id' => $company->id,
        ]);

        User::create([
            'name' => 'Staff Contoh',
            'email' => 'staff@contoh.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'company_id' => $company->id,
        ]);
    }
}
