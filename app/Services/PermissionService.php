<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

/**
 * Class PermissionService.
 */
class PermissionService
{

    public function getAll()
    {
        $data = Permission::query()->get();
        return $data;
    }
}
