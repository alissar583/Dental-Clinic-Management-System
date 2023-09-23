<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class RoleService.
 */
class RoleService
{

    public function addRoleWithPermissions($data)
    {
        DB::beginTransaction();
        $role = Role::query()->where('name', $data['role_name_en'])
            ->firstOrCreate([
                'name' => $data['role_name_en'],
                'name_ar' => $data['role_name_ar'],
            ]);
        if ($role->wasRecentlyCreated && isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        DB::commit();
        return ResponseHelper::success($role, true);
    }

    public function getAllRoles($request)
    {
        $roles = Role::query()->when($request->withPermissions, function ($q) {
            $q->with('permissions');
        })->get();
        return ['status' => true, 'data' => $roles];
    }

    public function getPermissionsByRole($role)
    {
        $data = $role->load('permissions');
        return ['status' => true, 'data' => $data];
    }

    public function updateRolePermissions($role, $premissions)
    {
        $role->syncPermissions($premissions);
        return ['status' => true, 'data' => $role];
    }

    public function deleteRolePermissions($role, $premission)
    {
        $role->revokePermissionTo($premission);
        return ['status' => true, 'data' => $role];
    }
}
