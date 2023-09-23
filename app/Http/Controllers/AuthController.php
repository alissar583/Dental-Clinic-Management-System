<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\AddRoleToUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\UpdatProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ReservationReminder;
use App\Services\AuthService;
use App\Services\TwilioService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
        # code...
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request);
        if ($result['status']) {
            // $result['data']['user'];
            $data = (new UserResource($result['data']))->toLogin();
            $data['token'] = $result['data']['token'];
            return ResponseHelper::success($data);
        } else {
            if ($result['message'] == 'Unauthorized')
                return ResponseHelper::error(null, $result['message'], 401);

            return ResponseHelper::error($result['message']);
        }
    }

    public function createAccount(StoreAccountRequest $request)
    {
        $result = $this->authService->createAccount($request);
        if ($result['status']) {
            $data = (new UserResource($result['data']))->toCreateAccount($request);
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error($result['message']);
        }
    }

    public function addRoleToUser(User $user, AddRoleToUserRequest $request)
    {
        $result = $this->authService->addRoleToUser($user,$request->validated());
        if ($result['status']) {
            return ResponseHelper::success($result['data']);
        } else {
            return ResponseHelper::error($result['message']);
        }
    }

    public function logout()
    {
        $result = $this->authService->logout();
        if ($result['success']) {
            return ResponseHelper::success($result['message']);
        } else {
            return ResponseHelper::error(__('locale.error'));
        }
    }

    public function refreshToken()
    {
        $result = $this->authService->refreshToken();
        if ($result['status']) {
            return ResponseHelper::success($result['data']);
        } else {
            return ResponseHelper::error($result['message']);
        }
    }

    public function myProfile()
    {
        $result = $this->authService->getProfile(auth()->user());
        $data = UserResource::make($result);
        return ResponseHelper::success($data);
    }

    public function getUserProfile(User $user)
    {
        $result = $this->authService->getProfile($user);
        $data = (new UserResource($result))->toGetUserProfile();
        return ResponseHelper::success($data);
    }

    public function updateMyProfile(UpdatProfileRequest $request)
    {
        $result = $this->authService->updateProfile($request->validated(), auth()->user());
        $data = UserResource::make($result);
        return ResponseHelper::success($data);
    }

    public function updateUserProfile(UpdatProfileRequest $request, User $user)
    {
        $result = $this->authService->updateProfile($request->validated(), $user);
        $data = UserResource::make($result);
        return ResponseHelper::success($data);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword($request->validated(), auth()->user());
        return ResponseHelper::success([], false, __('updated success'));
    }

    public function updatePhone(UpdatePhoneRequest $request)
    {
        $this->authService->updatePhone($request->validated(), auth()->user());
        return ResponseHelper::success([], false, __('updated success'));
    }


    public function test()
    {
        $user = User::query()->first();
        $user->notify(new ReservationReminder('test'));
        // $test = (new TwilioService())->send('test');
    }

}
