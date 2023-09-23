<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkingDaysRequest;
use App\Http\Resources\Admin\ClinicResource;
use App\Http\Resources\ClinicCollection;
use App\Models\Clinic;
use App\Models\WorkingDay;
use App\Rules\CheckWorcableIntersection;
use App\Services\ClinicService;
use Illuminate\Http\Request;

class WorkingDaysController extends Controller
{

    public function __construct(protected ClinicService $clinicService)
    {
    }

    public function show()
    {
        $result = auth()->user()->adminClinic->workingDays->load('day');
        $data = (new ClinicCollection($result))->toWorkingDays();
        return ResponseHelper::success($data);
    }

    public function store(WorkingDaysRequest $request)
    {
        $request->validate([
            'days' =>  [new CheckWorcableIntersection(auth()->user()->adminClinic)]
        ]);
        $result = $this->clinicService->addWorkingDays($request);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('created success'));
        }
    }

    //update from clinic (only one day)
    public function update(WorkingDaysRequest $request)
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
            'days' =>  [new CheckWorcableIntersection(auth()->user()->adminClinic)]
        ]);

        $result = $this->clinicService->addWorkingDays($request);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success(['data' => null], false, __('updated success'));
        }
    }

    //remove from clinic
    public function destroy(WorkingDay $day)
    {
        $result = $this->clinicService->removeWorkingDay($day);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('deleted success'));
        }
    }
}
