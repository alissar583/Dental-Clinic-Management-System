<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Doctor;
use Exception;

/**
 * Class SpecializationService.
 */
class SpecializationService
{

    public function addSpecializationToDoctor($doctor, $specializations)
    {
        foreach ($specializations as $specialization) {
            $certificateUrl = null;
            if (isset($specialization['certificate'])) {
                $certificateUrl = FileHelper::upload($specialization['certificate'], 'images/certificates');
            }
            $data[$specialization['id']] = [
                'doctor_id' => $doctor->id,
                'specialization_id' => $specialization['id'],
                'certificate' => $certificateUrl
            ];
        }
        $doctor->specializations()->syncWithoutDetaching($data);
        return ResponseHelper::success([], true);
    }
    public function removeSpecializationFromDoctor($doctor, $specializations)
    {
        $choosedSpecializations = $doctor->specializations()->whereIn('specialization_id', $specializations)->get();
        foreach ($choosedSpecializations as $specialization) {
            if ($specialization->pivot->certificate) 
                FileHelper::delete($specialization->pivot->certificate);
        }
        $doctor->specializations()->detach($specializations);
        return ResponseHelper::success([], true);
    }

    public function getSpecializationForDoctor($doctor)
    {
        $specializations = $doctor->specializations;
        return $specializations;
    }
}
