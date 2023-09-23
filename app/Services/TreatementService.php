<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Doctor;
use App\Models\DoctorPreview;
use App\Models\Patient;
use App\Models\Preview;
use App\Models\Treatement;
use Illuminate\Support\Facades\Auth;


/**
 * Class TreatementService.
 */
class TreatementService
{
    public function get($data)
    {
        $result = [];
        try {
            if (isset($data['patient_id'])) {
                $patient = Patient::findOrFail($data['patient_id']);
            } else {
                $patient = Auth::user()->patient;
            }

            $result = $patient->treatements()
                ->whereHas('preview', function ($q) use ($data) {
                    $q->when(isset($data['doctor_id']), function ($q) use ($data) {
                        $q->where('doctor_id', $data['doctor_id']);
                    })->when(isset($data['preview_id']), function ($q) use ($data) {
                        $q->where('preview_id', $data['preview_id']);
                    });
                })->with('preview:previews.id,name_ar,name_en')->with('doctor', function ($q) {
                    $q->select('doctors.id')
                        ->with('user:users.id,first_name,last_name,phone,photo');
                });

            if (isset($data['start']) && isset($data['end'])) {
                $result = $result->whereBetween('created_at', [$data['start'], $data['end']]);
            }
            if (isset($data['date'])) {
                $result = $result->where('created_at', $data['date']);
            }

            if (isset($data['order_by']) == 'asc') {
                $result->orderBy('created_at');
            } else {
                $result->orderByDesc('created_at');
            }

            $result = $result->paginate(20);
            return ResponseHelper::success($result, true);
        } catch (\Throwable $th) {
            return ResponseHelper::error(true);
        }
    }

    public function store(array $data)
    {
        $doctorPreview = DoctorPreview::where([
            'doctor_id' => $data['doctor_id'],
            'preview_id' => $data['preview_id']
        ])->firstOrFail();
        $data['doctor_preview_id'] = $doctorPreview->id;

        $data['cost'] = $data['cost'] ?? $doctorPreview->load('preview')->preview->cost;

        Treatement::query()->create($data);
        return true;
    }

    
}
