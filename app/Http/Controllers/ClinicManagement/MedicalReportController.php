<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Requests\UpdateMedicalReportRequest;
use App\Http\Resources\MedicalReportResource;
use App\Models\Illness;
use App\Models\MedicalReport;
use App\Models\Patient;
use App\Models\User;
use App\Services\MedicalReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class MedicalReportController extends Controller
{
    public function __construct(protected MedicalReportService $medicalReportService)
    {}

    public function update(MedicalReport $medicalReport, UpdateMedicalReportRequest $request)
    {
        $result = $this->medicalReportService->update($medicalReport, $request->validated());
        $data = MedicalReportResource::make($result);
        return ResponseHelper::success($data);
    }

    public function removeIllness(MedicalReport $medicalReport, Request $request)
    {
        $request->validate([
            'illness' => ['required', 'exists:illnesses,id']
        ]);
        $this->medicalReportService->removeIllness($medicalReport, $request->illness);
        return ResponseHelper::deleted();
    }

}
