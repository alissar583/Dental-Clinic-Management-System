<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Doctor;
use App\Models\Preview;
use Illuminate\Support\Facades\Auth;

/**
 * Class PreviewService.
 */
class PreviewService
{
    public function get($withDoctors = 0)
    {
        $result = Preview::all();
        if ($withDoctors) {
            $result->load(['doctor' => function ($q) {
                $q->with('user');
            }]);
        }

        return $result;
    }


    public function store($request)
    {
        $data = $request->only('previews');
        foreach ($data['previews'] as $item) {
            $item['created_at'] = $item['updated_at'] = now();
            $result[] = $item;
        }

        Preview::query()->insert($result);
        return ResponseHelper::success([], true);
    }

    public function update($request,  $preview)
    {
        $data = $request->only(['name_en', 'name_ar', 'cost']);

        $preview->update($data);
        return ResponseHelper::success([], true);
    }

    public function destroy($preview)
    {
        $preview->delete();
        return ResponseHelper::success([], true);
    }


    public function addPreviewsToDoctor($previews, $doctor)
    {
        $doctor->previews()->syncWithoutDetaching($previews);
        return ResponseHelper::success([], true);
    }

    public function detachPreviewsFromDoctor($previews, $doctor)
    {
        $doctor->previews()->detach($previews);
        return ResponseHelper::success([], true);
    }

    public function getDoctorPreviews($doctorId = null)
    {
        $result = [];
        if ($doctorId && (Auth::user()->account_type == 1 || Auth::user()->account_type == 3)) {
            $result = Doctor::query()->findOrFail($doctorId)->previews;
        } elseif (Auth::user()->account_type == 2) {
            $result = Auth::user()->doctor->previews;
        }

        return $result;
    }
}
