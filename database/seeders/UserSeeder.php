<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@footprint.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // Create POL Admin 1
        User::create([
            'name' => 'POL Admin 1',
            'email' => 'poladmin1@footprint.com',
            'password' => Hash::make('password'),
            'role' => 'pol_admin',
            'email_verified_at' => now(),
        ]);

        // Create POL Admin 2
        User::create([
            'name' => 'POL Admin 2',
            'email' => 'poladmin2@footprint.com',
            'password' => Hash::make('password'),
            'role' => 'pol_admin',
            'email_verified_at' => now(),
        ]);
    }
}
