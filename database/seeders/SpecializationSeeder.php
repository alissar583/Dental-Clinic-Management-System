<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialization::query()->delete();

        $specializations = [
            [
                'name_ar' => 'test ar',
                'name_en' => 'test en'
            ],
            [
                'name_ar' => 'test2 ar',
                'name_en' => 'test2 en'
            ],
        ];
        DB::table('specializations')->insert($specializations);
    }
}
