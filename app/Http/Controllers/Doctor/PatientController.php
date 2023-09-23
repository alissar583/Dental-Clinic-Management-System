<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalReportResource;
use App\Models\Illness;
use App\Models\Patient;
use App\Models\User;
use App\Services\MedicalReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function __construct(protected MedicalReportService $medicalReportService)
    {
    }
    public function getPatientMedicalReport(Patient $patient)
    {
        $pdf = $this->medicalReportService->getByPatient($patient);
        return $pdf->download('fileName.pdf');
    }

    public function getMyMedicalReport()
    {
        $patient = auth()->user()->patient;
        $pdf = $this->medicalReportService->getByPatient($patient);
        return $pdf->download('fileName.pdf');
 
    }
}
