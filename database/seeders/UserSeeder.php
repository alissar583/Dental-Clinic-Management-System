<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    use HasRoles;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'System',
                'last_name' => 'Adminstater',
                'account_type' => 1,
                'password' => Hash::make('admina'),
                'phone' => "0999999999",
                'birth_date' =>  now(),
                'clinic_id' => 1
            ],
            [
                'first_name' => 'Doctor',
                'last_name' => 'Doctor',
                'account_type' => 2,
                'password' => Hash::make('doctor'),
                'phone' => "0988888888",
                'birth_date' =>  now(),
                'clinic_id' => 1
            ],
            [
                'first_name' => 'Secretary',
                'last_name' => 'Secretary',
                'account_type' => 3,
                'password' => Hash::make('secretary'),
                'phone' => "0977777777",
                'birth_date' =>  now(),
                'clinic_id' => 1
            ],
            [
                'first_name' => 'Patient',
                'last_name' => 'Patient',
                'account_type' => 4,
                'password' => Hash::make('patient'),
                'phone' => "0966666666",
                'birth_date' =>  now(),
                'clinic_id' => 1
            ]
        ]);
        $user = new User();
        $user->find(1)->assignRole(Role::find(1));
        $user->find(2)->doctor()->create();
        $user->find(3)->secretary()->create();
        $user->find(4)->patient()->create();

    }
}
