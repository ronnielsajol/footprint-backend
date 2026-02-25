<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VipSeeder::class,
            PolDeploymentSeeder::class,
            WAscDeploymentSeeder::class,
            // EventSeeder::class, // Deprecated - replaced by POL and W ASC deployment seeders
        ]);
    }
}
