<?php

namespace App\Http\Controllers\Patient;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetMyTreatementsRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\TreatementResource;
use App\Models\Patient;
use App\Models\Treatement;
use App\Services\DoctorService;
use App\Services\TreatementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatementController extends Controller
{
    public function __construct(protected TreatementService $treatementService,protected DoctorService $doctorService){}


    public function getMyTreatements(GetMyTreatementsRequest $request)
    {
        $treatements = $this->treatementService->get($request->validated());
        $data = TreatementResource::collection($treatements['data']);
        return ResponseHelper::success($data);
    }

    public function doctors(Request $request)
    {
        $result = $this->doctorService->getDoctors($request->name,Auth::user()->clinic_id);
        $data = DoctorResource::collection($result)->additional(['success' => true, 'message' => 'success']);
        if ($result) {
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }
}
