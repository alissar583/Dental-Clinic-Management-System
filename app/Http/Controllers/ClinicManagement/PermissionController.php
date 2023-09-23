<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    public function index()
    {
        $result = $this->permissionService->getAll();
        return ResponseHelper::success($result, false);
    }
}
