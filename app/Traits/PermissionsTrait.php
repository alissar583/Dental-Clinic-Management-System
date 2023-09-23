<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait PermissionsTrait
{

    public function allPermissions()
    {

        $indirectPermissions = $this
            ->select('permissions.id', 'permissions.name')
            ->join('model_has_roles as mr', 'mr.model_id', $this->getTable() . '.id')
            ->where('mr.model_type', $this->getMorphClass())
            ->where('mr.model_id', $this->id)
            ->join('role_has_permissions as rp', 'rp.role_id', 'mr.role_id')
            ->join('permissions', 'permissions.id', 'rp.permission_id')
            ->get();


        $data['permissions'] = $indirectPermissions;

        $directPermissions = $this->permissions()->get(['id', 'name']);

        $directPermissions->each(function ($role) {
            $role->setHidden(['pivot']);
        });

        if ($directPermissions)
            $data['permissions'] = array_merge($data['permissions']->toArray(), $directPermissions->toArray());

        return $data['permissions'] = array_unique($data['permissions'], SORT_REGULAR);
    }
}
