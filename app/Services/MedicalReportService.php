<?php

namespace App\Services;

use App\Http\Resources\MedicalReportResource;
use App\Models\Illness;
use App\Models\MedicalReport;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;

/**
 * Class MedicalReportService.
 */
class MedicalReportService
{

    public function getByPatient(Patient $patient)
    {
        $patient->load(['user:id,first_name,last_name,phone,birth_date', 'medicalReport' => function ($query) {
            $query->with('illnesses');
        }])
            ->load(['treatements' => function ($q) {
                $q->select(['treatements.id', 'patient_id', 'doctor_preview_id'])
                    ->with([
                        // 'reservations:id,date,treatement_id,medicines,diagnostics',
                        'doctor.user:id,first_name,last_name,phone',
                        'preview',
                        'reservations' => function ($query) {
                            $query->select(['id', 'date', 'treatement_id', 'medicines', 'diagnostics'])->with(['secretary.user', 'media']);
                        }
                    ]);
            }]);
        $data = MedicalReportResource::make($patient)->resolve();
        $illnesses = Illness::query()->get();
        $data['all_illnesses'] = $illnesses;
        $pdf = Pdf::loadView('medicalReport', $data);
        return $pdf;
    }

    public function update(MedicalReport $medicalReport, $data)
    {
        $medicalReport->update($data);
        if (in_array('illnesses', $data)) {
            $medicalReport->illnesses()->syncWithoutDetaching($data['illnesses']);
        }
        return $medicalReport->load('illnesses');
    }

    public function removeIllness(MedicalReport $medicalReport, $illness)
    {
        $medicalReport->illnesses()->detach($illness);
    }
}
