<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function __construct(protected RoleService $roleService)
    {
        # code...
    }
    public function store(StoreRoleRequest $request)
    {
        $result = $this->roleService->addRoleWithPermissions($request);
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            $data = RoleResource::make($result['data']);
            return ResponseHelper::success($data);
        }
    }

    public function index(Request $request)
    {
        $result = $this->roleService->getAllRoles($request);
        if (!$result['status']) {
            return ResponseHelper::error($result['message']);
        } else {
            $data = RoleResource::collection($result['data']);
            return ResponseHelper::success($data);
        }
    }

    public function getPermissiosByRole($role)
    {
        $roleWithPermissions = Role::query()->with('permissions')->findOrFail($role);
        $data = RoleResource::make($roleWithPermissions);
        return ResponseHelper::success($data);
    }

    public function updateRolePermissions(Role $role, UpdateRolePermissionsRequest $request)
    {
        $result = $this->roleService->updateRolePermissions($role, $request->permissions);
        if (!$result['status']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __("updated success"));
        }
    }


    public function deleteRolePermissions(Role $role, Request $request)
    {
        $result = $this->roleService->deleteRolePermissions($role, $request->permissions);
        if (!$result['status']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success([], false, __('deleted'));
        }
    }

    
}
