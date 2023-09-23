<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePreviewRequest;
use App\Http\Requests\UpdatePreviewRequest;
use App\Http\Resources\PreviewResource;
use App\Models\Preview;
use App\Services\PreviewService;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function __construct(public PreviewService $previewService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = $this->previewService->get($request->withDoctors);
        $data = PreviewResource::collection($result);

        if ($data) {
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error(false, $result['message']);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePreviewRequest $request)
    {
        $result = $this->previewService->store($request);

        if ($result['success']) {
            return ResponseHelper::created();
        } else {
            return ResponseHelper::error(false, $result['message']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePreviewRequest $request, Preview $preview)
    {
        $result = $this->previewService->update($request, $preview);

        if ($result['success']) {
            return ResponseHelper::updated();
        } else {
            return ResponseHelper::error(false, $result['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preview $preview)
    {
        $result = $this->previewService->destroy($preview);
        if ($result['success']) {
            return ResponseHelper::deleted();
        } else {
            return ResponseHelper::error(false, $result['message']);
        }
    }

}
