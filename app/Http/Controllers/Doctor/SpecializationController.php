<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddSpecializationToDoctorRequest;
use App\Http\Resources\SpecializationResource;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Services\SpecializationService;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{

    public function __construct(public SpecializationService $specializationService){}

   
    public function addSpecializationToDoctor(Doctor $doctor, AddSpecializationToDoctorRequest $request)
    {
         $result = $this->specializationService->addSpecializationToDoctor($doctor, $request->specializations);
        if($result['success']) {
            return ResponseHelper::success([], false, __('done successfully'));
        }else {
            return ResponseHelper::error( false, $result['message'], 404);
        }
    }
    

    public function removeSpecializationFromDoctor(Request $request, Doctor $doctor)
    {
       return $result = $this->specializationService->removeSpecializationFromDoctor($doctor, $request->specializations);
        if($result['success']) {
            return ResponseHelper::success([], false, __('done successfully'));
        }else {
            return ResponseHelper::error( false, $result['message'], 404);
        }
       
    }
}
