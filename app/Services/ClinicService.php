<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\WorkingDay;
use App\Rules\CheckWorcableIntersection;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class ClinicService.
 */
class ClinicService
{
    public function store($request)
    {
        $clinic = auth()->user()->adminClinic;

        $data = [];
        $clinicData = $request->only(['name', 'phone', 'lat', 'long']);
        $address = $request->only(['city', 'country', 'area', 'street', 'building_number', 'floor_number', 'note']);
        if ($request->hasFile('logo'))
            $clinicData['logo'] = FileHelper::upload($request->logo, '/clinics/logo');

        try {
            DB::beginTransaction();

            $data = $clinic->create($clinicData);
            $clinic->address()->create($address);

            DB::commit();
            return ResponseHelper::success($data, 1);
        } catch (Exception $e) {

            DB::rollBack();
            return ResponseHelper::error(1);
        }
    }

    public function update($request)
    {
        $clinic = auth()->user()->adminClinic;
        $clinicData = $request->only(['name', 'phone', 'lat', 'long']);
        $address = $request->only(['city', 'country', 'area', 'street', 'building_number', 'floor_number', 'note']);
        if ($request->hasFile('logo')) {
            if ($clinic->logo)
                $clinicData['logo'] = FileHelper::replace($request->logo, $clinic->logo, '/clinics/logo');
            $clinicData['logo'] = FileHelper::upload($request->logo, '/clinics/logo');
        }
        // try {
        DB::beginTransaction();

        if ($clinicData) {
            $clinic->update($clinicData);
        }
        if ($address) {
            $clinic->address()->updateOrCreate([
                'addressable_id' => $clinic->id,
                'addressable_type' => Clinic::class
            ], [
                'city' => isset($address['city']) ? $address['city'] : null,
                'country' => isset($address['country']) ? $address['country'] : null,
                'area' => isset($address['area']) ? $address['area'] : null,
                'street' => isset($address['street']) ? $address['street'] : null,
                'building_number' => isset($address['building_number']) ? $address['building_number'] : null,
                'floor_number' => isset($address['floor_number']) ? $address['floor_number'] : null,
                'note' => isset($address['note']) ? $address['note'] : null
            ]);
        }

        DB::commit();
        return ResponseHelper::success($clinic, 1);
        // } catch (Exception $e) {
        DB::rollBack();
        //     return ResponseHelper::error(1);
        // }
    }

    public function addWorkingDays($request)
    {
        auth()->user()->adminClinic->workingDays()->createMany($request->days);
        return ResponseHelper::success([], 1);
    }

    public function updateWorkingDay($request, $day)
    {
        $day->update([
            'day_id' => $request->days[0]['day_id'],
            'from' => $request->days[0]['from'],
            'to' => $request->days[0]['to']
        ]);

        return ResponseHelper::success([], 1);
    }

    public function removeWorkingDay($day)
    {
        $day->delete();

        return ResponseHelper::success([], 1);
    }
}
