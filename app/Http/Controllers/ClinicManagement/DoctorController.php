<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddPreviewsToDoctorRequest;
use App\Http\Requests\PreviewsDoctorRequest;
use App\Http\Requests\WorkingDaysRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PreviewResource;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Services\DoctorService;
use App\Services\PreviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function __construct(protected DoctorService $doctorService, protected PreviewService $previewService)
    {
    }

    public function index(Request $request)
    {
        $result = $this->doctorService->getDoctorsWithSpecializations($request->name, Auth::user()->adminClinic->id);
        $data = DoctorResource::collection($result)->additional(['success' => true, 'message' => 'success']);
        if ($result) {
            return $data;
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }

    public function getDoctorPreviews(Request $request)
    {
        $result = $this->previewService->getDoctorPreviews($request->doctor_id);
        $data = PreviewResource::collection($result);

        if (isset($result)) {
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }
    public function addPreviewsToDoctor(PreviewsDoctorRequest $request, Doctor $doctor)
    {

        $result = $this->previewService->addPreviewsToDoctor($request->previews, $doctor);
        if ($result) {
            return $result;
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }

    public function detachPreviewsFromDoctor(PreviewsDoctorRequest $request, Doctor $doctor)
    {
        $result = $this->previewService->detachPreviewsFromDoctor($request->previews, $doctor);
        if ($result) {
            return $result;
        } else {
            return ResponseHelper::error(false, __('server error'));
        }
    }
}
