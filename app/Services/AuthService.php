<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Doctor;
use App\Models\MedicalReport;
use App\Models\Patient;
use App\Models\User;
use App\Traits\PermissionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

/**
 * Class AuthService.
 */
class AuthService
{

    public function login($request)
    {
        $credentials = $request->only('phone', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ['status' => false, 'message' => 'Unauthorized'];
            }
        } catch (JWTException $e) {
            return ['status' => false, 'message' => 'could_not_create_token'];
        }
        $user = auth()->user();

        $data['user'] = $user;
        $data['permissions'] =  $user->allPermissions();

        $data['token'] = $token;
        return ['status' => true, 'data' => $data];
    }

    public function createAccount($request)
    {

        $userData = $request->only(['first_name', 'last_name', 'photo', 'birth_date', 'active', 'phone']);
        $user = auth()->user();
        $userData['account_type'] = $user->account_type == 1 ? $request->account_type : AccountType::Patient;
        $clinic = $user->account_type == 1 ? $user->adminClinic : $user->myClinic;
        try {
            DB::beginTransaction();
            if ($request->hasFile('photo'))
                $userData['photo'] = FileHelper::upload($request->photo, 'users/images');

            $userData['clinic_id'] = $clinic->id;
            $userData['password'] = '123456';

            $user = User::query()->create($userData);

            if (auth()->user()->account_type == 3)
                $user->assignRole(4);
            else {
                $user->syncRoles($request->roles);
                $user->syncPermissions($request->permissions);
            }

            if ($user->account_type == AccountType::Doctor) {
                $user->doctor()->create();
            } elseif ($user->account_type == AccountType::Secretarial) {
                $user->secretary()->create();
            } elseif ($user->account_type == AccountType::Patient) {
                $medicalReportData = $request->only(['medicine', 'else_illnesses']);
                $patient = $user->patient()->create();
                $medicalReportData['oid'] = FileHelper::generateOid(new MedicalReport);
                $medicalReport = $patient->medicalReport()->create($medicalReportData);
                $medicalReport->illnesses()->syncWithoutDetaching($request->illnesses);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => __('locale.error')];
        }
        return ['status' => true, 'data' => $user];
    }

    public function removeRoleFromUser($user, $role_id)
    {
        $user->removeRole($role_id);
        return ['status' => true, 'message' => __('locale.success')];
    }

    public function addRoleToUser($user, $request)
    {

        if (isset($request['permissions']))
            $user->syncPermissions($request['permissions']);

        if (isset($request['roles'])) {
            $user->roles()->detach();
            $user->syncRoles($request['roles']);
        }

        return ['status' => true, 'data' => $user];
    }


    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);
            return ['status' => true, 'data' => $token];
        } catch (TokenInvalidException $e) {
            return ['status' => false, 'message' => __('locale.error')];
        }
    }


    public function logout()
    {
        auth()->logout();
        return ResponseHelper::success([], true, __('logout success'));
    }

    public function getProfile($user)
    {

        if ($user->account_type == AccountType::Doctor) {
            $user->load(['doctor' => function ($query) {
                $query->with(['specializations', 'workingDays' => function ($q) {
                    $q->select(['workable.*', 'days.name_' . app()->getLocale()])
                        ->join('days', 'days.id', 'workable.day_id');
                }]);
            }]);
        } elseif ($user->account_type == AccountType::Patient) {
            $user->load(['patient' => function ($query) {
            }]);
        }
        ///////
        $data['user'] = $user;
        $data['roles'] =  $user->getAllPermissions();
        /////////
        return $data;
    }
    public function updateProfile($data, User $user)
    {
        if (array_key_exists('photo', $data)) {
            $data['photo'] = FileHelper::upload($data['photo'], 'users/images');
        }
        $user->update($data);
        return $user->fresh();
    }

    public function updatePhone($data, User $user)
    {
        $user->update([
            'phone' => $data['phone']
        ]);
    }

    public function changePassword($data, User $user)
    {
        $user->update([
            'password' => $data['password']
        ]);
    }
}
