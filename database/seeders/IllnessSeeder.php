<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IllnessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('illnesses')->delete();
        DB::table('illnesses')->insert([
            [
                'name_ar' => 'سكر',
                'name_en' => 'Diabetes',
            ],
            [
                'name_ar' => 'كلى',
                'name_en' => 'Kidney',
            ],
            [
                'name_ar' => 'ضغط',
                'name_en' => 'Hypertension',
            ],
            [
                'name_ar' => 'قلب',
                'name_en' => 'heart disease',
            ],
            [
                'name_ar' => 'حساسية',
                'name_en' => 'Allergy',
            ],
            [
                'name_ar' => 'سيولة في الدم',
                'name_en' => 'Blood Clot',
            ],
            [
                'name_ar' => 'الكبد الوبائي',
                'name_en' => 'Hepatitis',
            ],
            [
                'name_ar' => 'غدة درقية',
                'name_en' => 'Thyroid',
            ],
            [
                'name_ar' => 'سرطان',
                'name_en' => 'Cancer',
            ],
            [
                'name_ar' => 'روماتيزم',
                'name_en' => 'Rheumatism',
            ],
            [
                'name_ar' => 'مدخن',
                'name_en' => 'Smoker',
            ],
            [
                'name_ar' => 'حمل',
                'name_en' => 'Pregnancy',
            ]
        ]);
    }
}
