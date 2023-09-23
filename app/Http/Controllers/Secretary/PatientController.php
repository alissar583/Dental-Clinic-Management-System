<?php

namespace App\Http\Controllers\Secretary;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Resources\IllnessResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\IllnessService;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(protected AuthService $authService, protected IllnessService $illnessService
    ,protected PatientService $patientService)
    {
    }
    public function createAccount(CreatePatientRequest $request)
    {
        $result = $this->authService->createAccount($request);
        if ($result['status']) {
            $data = (new UserResource($result['data']))->toCreateAccount($request);
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error($result['message']);
        }
    }

    public function getIllnesses()
    {
        $result = $this->illnessService->index();
        $data = IllnessResource::collection($result);
        return ResponseHelper::success($data);
    }

    public function index(){
        $result = $this->patientService->getPatient();
        foreach($result as $item) {
            $item['photo'] = asset($item['photo']);
        }
        return ResponseHelper::success($result);
    }


}
