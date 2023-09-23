<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('days')->insert([
            [
                'name_en' => 'Sunday',
                'name_ar' => 'الأحد'
            ],
            [
                'name_en' => 'Monday',
                'name_ar' => 'الإثنين'
            ],
            [
                'name_en' => 'Tuesday',
                'name_ar' => 'الثلاثاء'
            ],
            [
                'name_en' => 'Wednesday',
                'name_ar' => 'الأربعاء'
            ],
            [
                'name_en' => 'Thursday',
                'name_ar' => 'الخميس'
            ],
            [
                'name_en' => 'Friday',
                'name_ar' => 'الجمعة'
            ],
            [
                'name_en' => 'Saturday',
                'name_ar' => 'السبت'
            ]
        ]);
    }
}
