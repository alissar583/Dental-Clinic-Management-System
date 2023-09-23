<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecializationRequest;
use App\Http\Requests\UpdateSpecializationRequest;
use App\Http\Resources\SpecializationResource;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specializationsQuery = Specialization::query();
        if(request()->get('with_doctors') == true) {
            $specializationsQuery->with('doctors.user');
        }
        $specializations = $specializationsQuery->get();
        $data = SpecializationResource::collection($specializations);
        return ResponseHelper::success($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationRequest $request)
    {
        Specialization::query()->create($request->only(['name_ar', 'name_en']));
        return ResponseHelper::created();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationRequest $request,Specialization $specialization)
    {
        $specialization->update($request->only(['name_ar','name_en']));
        return ResponseHelper::updated();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return ResponseHelper::deleted();
    }
}
