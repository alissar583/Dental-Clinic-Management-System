<?php

namespace App\Http\Controllers\Secretary;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetTreatemntsRequest;
use App\Http\Requests\StoreTreatemantRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\TreatementResource;
use App\Models\User;
use App\Services\DoctorService;
use App\Services\TreatementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatementController extends Controller
{
    public function __construct(protected TreatementService $treatementService,protected DoctorService $doctorService)
    {}

    public function index(GetTreatemntsRequest $request)
    {
        $result = $this->treatementService->get($request->validated());
        $data = TreatementResource::collection($result['data']);

        if ($result['success']) {
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error($result['message']);
        }
    }

    public function store(StoreTreatemantRequest $request)
    {
        $this->treatementService->store($request->validated());
        return ResponseHelper::success();
    }

    public function doctors(Request $request)
    {
        $result = $this->doctorService->getDoctors($request->name,Auth::user()->clinic_id);
        $data = DoctorResource::collection($result)->additional(['success' => true, 'message' => 'success']);
        if ($result) {
            return $data;
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }

  }
