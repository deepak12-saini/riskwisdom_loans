<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => (string) env('ADMIN_USERNAME', 'admin')],
            [
                'name' => 'Administrator',
                'email' => (string) env('ADMIN_EMAIL', 'admin@riskwisdomloans.com.au'),
                'password' => Hash::make((string) env('ADMIN_PASSWORD', 'Admin@12345')),
                'is_admin' => true,
                'role' => User::ROLE_ADMIN,
                'permissions' => null,
            ]
        );
    }
}
