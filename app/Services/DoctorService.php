<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Doctor;
use App\Rules\CheckWorcableIntersection;

/**
 * Class DoctorService.
 */
class DoctorService
{

    public function getDoctorsWithSpecializations($name, $clinic_id)
    {
        $data = Doctor::query()->whereHas('user' , function ($query) use ($name, $clinic_id) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $name . '%');
            })
                ->where('clinic_id', $clinic_id);
        })->with(['user', 'specializations'])->paginate(20);
        return $data;
    }

    
    public function getDoctors($name, $clinic_id)
    {
        $data = Doctor::query()->whereHas('user' , function ($query) use ($name, $clinic_id) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $name . '%');
            })
                ->where('clinic_id', $clinic_id);
        })->with('user')->paginate(20);
        return $data;
    }

    public function addWorkingDays($request, Doctor $doctor)
    {
        $doctor->workingDays()->createMany($request->days);
        return ResponseHelper::success([], 1);
    }
}
