<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class PermissionSeeder extends Seeder
{
    use HasRoles;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Permission::query()->create([
            'name' => 'show clinic accounts',
            'name_ar' => 'عرض حسابات العيادة',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'create clinic accounts',
            'name_ar' => 'انشاء حسابات العيادة',
            'guard_name' => 'api'
        ]);
        Permission::query()->create([
            'name' => 'edit clinic accounts',
            'name_ar' => 'تعديل حسابات العيادة',
            'guard_name' => 'api'
        ]);
        Permission::query()->create([
            'name' => 'delete clinic accounts',
            'name_ar' => 'تعطيل حسابات العيادة',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'add specialization',
            'name_ar' => 'اضافة اختصاص',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'edit specialization',
            'name_ar' => 'تعديل اختصاص',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'delete specialization',
            'name_ar' => 'حذف اختصاص',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'show specialization',
            'name_ar' => 'عرض اختصاص',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'add specialization to doctor',
            'name_ar' => 'اضافة اختصاص الى طبيب' ,
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'delete specialization from doctor',
            'name_ar' => 'حذف اختصاص من طبيب',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'add doctor working days',
            'name_ar' => 'اضافة ايام دوام الطبيب',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'edit doctor working days',
            'name_ar' => 'تعديل ايام دوام الطبيب',
            'guard_name' => 'api'
        ]);


        Permission::query()->create([
            'name' => 'delete doctor working days',
            'name_ar' => 'حذف ايام دوام الطبيب',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'delete clinic working days',
            'name_ar' => 'حذف ايام دوام العيادة',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'add clinic working days',
            'name_ar' => 'اضافة ايام دوام العيادة',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'edit clinic working days',
            'name_ar' => 'تعديل ايام دوام العيادة',
            'guard_name' => 'api'
        ]);

        Permission::query()->create([
            'name' => 'show working days',
            'name_ar' => 'عرض ايام الدوام ',
            'guard_name' => 'api'
        ]);

        Role::find(1)->syncPermissions(Permission::all());
    }
}
