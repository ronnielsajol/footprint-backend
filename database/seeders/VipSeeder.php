<?php

namespace Database\Seeders;

use App\Models\Vip;
use Illuminate\Database\Seeder;

class VipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vips = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'contact_number' => '+63 912 345 6789',
                'email' => 'juan.delacruz@example.com',
                'birth_date' => '1980-05-15',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'contact_number' => '+63 923 456 7890',
                'email' => 'maria.santos@example.com',
                'birth_date' => '1985-08-22',
                'created_by' => 2, // POL Admin 1
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Reyes',
                'contact_number' => '+63 934 567 8901',
                'email' => null,
                'birth_date' => '1975-12-10',
                'created_by' => 3, // POL Admin 2
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Garcia',
                'contact_number' => '+63 945 678 9012',
                'email' => 'ana.garcia@example.com',
                'birth_date' => '1990-03-18',
                'created_by' => 2, // POL Admin 1
            ],
        ];

        foreach ($vips as $vip) {
            Vip::create($vip);
        }
    }
}
