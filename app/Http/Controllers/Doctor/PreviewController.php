<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePreviewRequest;
use App\Http\Requests\UpdatePreviewRequest;
use App\Http\Resources\PreviewResource;
use App\Models\Preview;
use App\Services\PreviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreviewController extends Controller
{
    public function __construct(public PreviewService $previewService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->previewService->getDoctorPreviews();
        $result = PreviewResource::collection($result);

        if($result){
            return ResponseHelper::success($result);
        }else{
            return ResponseHelper::error(false,$result['message']);
        }
    }
   
}
