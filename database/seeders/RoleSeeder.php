<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'name_ar'=>'طبيب مشرف',
                'guard_name' => 'api'
            ], [
                'name' => 'Doctor',
                'name_ar'=>'طبيب',
                'guard_name' => 'api'
            ], [
                'name' => 'Secretary',
                'name_ar'=>'سكرتير',
                'guard_name' => 'api'
            ], [
                'name' => 'Patient',
                'name_ar'=>'مريض',
                'guard_name' => 'api'
            ]
        ]);
    }
}
