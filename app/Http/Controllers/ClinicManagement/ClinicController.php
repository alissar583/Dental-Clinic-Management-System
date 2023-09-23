<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClinicRequest;
use App\Http\Requests\UpdateClinicRequest;
use App\Http\Requests\WorkingDaysRequest;
use App\Http\Resources\Admin\ClinicResource;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\WorkingDay;
use App\Services\ClinicService;
use Illuminate\Http\Request;
use Spatie\LaravelIgnition\Http\Requests\UpdateConfigRequest;

class ClinicController extends Controller
{
    public function __construct(protected ClinicService $clinicService)
    {
    }


    public function show(Request $request)
    {
        //TODO after fix createAccount : add -> show to client 
        $result = auth()->user()->adminClinic->load('address');
        // return $result;
        $data = (new ClinicResource($result))->toArray($request);
        return ResponseHelper::success($data);
    }

public function store(StoreClinicRequest $request)
    {
        $result = $this->clinicService->store($request);
        return $this->response($result);
    }


    public function update(UpdateClinicRequest $request)
    {
       return $result = $this->clinicService->update($request);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            $data = (new ClinicResource($result['data']))->toUpdate();
            return ResponseHelper::success($data);
        }
    }
}
