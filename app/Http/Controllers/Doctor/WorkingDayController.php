<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkingDaysRequest;
use App\Http\Resources\ClinicCollection;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\WorkingDay;
use App\Rules\CheckWorcableIntersection;
use App\Services\ClinicService;
use App\Services\DoctorService;
use Illuminate\Http\Request;

class WorkingDayController extends Controller
{
    public function __construct(protected DoctorService $doctorService, protected ClinicService $clinicService)
    {
    }


    public function show(Request $request)
    {
        if (auth()->user()->account_type == 1)
            $doctor = Doctor::findOrFail($request->doctor_id);
        else
            $doctor = auth()->user()->doctor;

        $result = $doctor?->workingDays;
        $data = (new ClinicCollection($result))->toWorkingDays();
        return ResponseHelper::success($data);
    }

    public function store(WorkingDaysRequest $request, Doctor $doctor)
    {
        $request->validate([
            'days' =>  [new CheckWorcableIntersection($doctor, 'doctor')]
        ]);
        $result = $this->doctorService->addWorkingDays($request, $doctor);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('created success'));
        }
    }

    public function update(Doctor $doctor, WorkingDaysRequest $request)
    {
        $dataArr = [];
        $data = $request->all();
        foreach ($data as $items) {
            foreach ($items['times'] as $time) {
                if ($time['from'] > $time['to']) {
                    return ResponseHelper::error(null, 'from time is after to time');
                }
                $dataArr[] = [
                    'day_id' => $items['id'],
                    'from' => $time['from'],
                    'to' => $time['to'],
                ];
            }
        }
        $request['days'] = $dataArr;
        $request->validate([
            'days' =>  [new CheckWorcableIntersection($doctor, 'doctor')]
        ]);

        $result = $this->doctorService->addWorkingDays($request, $doctor);

        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('updated success'));
        }
    }

    public function destroy($day)
    {
        $day = WorkingDay::query()->find($day);
        $result = $this->clinicService->removeWorkingDay($day);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('deleted success'));
        }
    }
}
