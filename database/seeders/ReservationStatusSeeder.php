<?php

namespace Database\Seeders;

use App\Models\ReservationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReservationStatus::query()->delete();
        DB::table('reservation_statuses')->insert([
            [
                'name_ar'=>'معلَّق',
                'name_en'=>'Pending'
            ],[
                'name_ar'=>'مؤكَّد',
                'name_en'=>'Confirmed'
            ],[
                'name_ar'=>'ملغي',
                'name_en'=>'Cancelled'
            ],
        ]);
    }
}
