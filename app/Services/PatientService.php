<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\MedicalReport;
use App\Models\Patient;

class PatientService
{
    public function getPatient()
    {
        // if (auth()->user()->account_type == 3) {
            $result = auth()->user()->myClinic()->with('users', function ($q) {
                $q->where('account_type', 4);
            })->get()->pluck('users')->flatten();
        // }    

        return ResponseHelper::paginate($result);
    }

}
