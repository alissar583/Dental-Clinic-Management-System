<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ClinicSeeder::class,
            DaySeeder::class,
            SpecializationSeeder::class,
            PermissionSeeder::class,
            ReservationStatusSeeder::class,
            PreviewSeeder::class,
            IllnessSeeder::class
        ]);
    }
}