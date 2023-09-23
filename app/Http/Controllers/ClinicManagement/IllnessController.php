<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIllenessRequest;
use App\Http\Requests\UpdateIllenessRequest;
use App\Http\Resources\IllnessResource;
use App\Models\Illness;
use App\Services\IllnessService;
use Illuminate\Http\Request;

class IllnessController extends Controller
{
    public function __construct(protected IllnessService $illnessService)
    {}

    public function index()
    {
        $result = $this->illnessService->index();
        $data = IllnessResource::collection($result);
        return ResponseHelper::success($data);
    }

    public function store(StoreIllenessRequest $request)
    {
        $result = $this->illnessService->store($request->validated());
        $data = IllnessResource::make($result);
        return ResponseHelper::success($data);
    }

    public function update(UpdateIllenessRequest $request, Illness $illness)
    {
        $result = $this->illnessService->update($illness, $request->validated());
        $data = IllnessResource::make($result);
        return ResponseHelper::success($data);
    }

    public function delete(Illness $illness)
    {
        $illness->delete();
        return ResponseHelper::deleted();
    }
}
