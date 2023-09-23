<?php

namespace Database\Seeders;

use App\Helpers\FileHelper;
use App\Models\MedicalReport;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::query()->first();
        $patient->medicalReport()->create([
            'oid' => FileHelper::generateOid(new MedicalReport())
        ]);
    }
}
