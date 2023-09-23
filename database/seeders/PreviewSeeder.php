<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('previews')->insert([
            [
                'name_ar' => 'اختبار',
                'name_en' => 'test',
                'cost' => 50000
            ],
            [
                'name_ar' => '2اختبار',
                'name_en' => 'test2',
                'cost' => 10000
            ]
        ]);

        DB::table('doctor_preview')->insert([
            [
                'doctor_id' => 2,
                'preview_id' => 1
            ],
            [
                'doctor_id' => 2,
                'preview_id' => 2
            ]
        ]);

        DB::table('treatements')->insert([
            [
                'cost' => 50000,
                'doctor_preview_id' => 1,
                'patient_id' => 4
            ],
            [
                'cost' => 50000,
                'doctor_preview_id' => 2,
                'patient_id' => 4
            ]
        ]);
    }
}
