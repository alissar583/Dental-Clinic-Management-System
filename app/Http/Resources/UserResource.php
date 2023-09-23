<?php

namespace App\Http\Resources;

use App\Enums\AccountType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this['user'];
        if(!isset($this['user'])) {
            $user = $this;
        }

        $data = [
            /////////
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'account_type' => AccountType::getKey((int)$user['account_type']),
            'photo' => $user['photo'] != null ? asset($user['photo']) : null,
            'birth_date' => $user['birth_date'],
            'active' => $user['active'],
            'phone' => $user['phone'],
            'clinic_id' => $user['clinic_id'],
            /////////
            'created_at' => $user['created_at']?->format('y-m-d') ?? '',
            'permissions' => isset($this['permissions']) ? $this['permissions'] : []
        ];
        if ($user->doctor)
            $data['doctor'] =  DoctorResource::make($user->doctor);
        if ($user->patient)
            $data['patient'] = PatientResource::make($user->patient);

        return $data;
    }

    public function toGetUserProfile(){
        $user = $this['user'];
        if(!isset($this['user'])) {
            $user = $this;
        }

        $data = [
            /////////
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'account_type' => AccountType::getKey((int)$user['account_type']),
            'photo' => $user['photo'] != null ? asset($user['photo']) : null,
            'birth_date' => $user['birth_date'],
            'active' => $user['active'],
            'phone' => $user['phone'],
            'clinic_id' => $user['clinic_id'],
            /////////
            'created_at' => $user['created_at']?->format('y-m-d') ?? '',
            'roles' => $user['roles']
        ];
        if ($user->doctor)
            $data['doctor'] =  DoctorResource::make($user->doctor);
        if ($user->patient)
            $data['patient'] = PatientResource::make($user->patient);

        return $data;
    }


    public function toCreateAccount($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'password' => 123456,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'account_type' => AccountType::getKey((int)$this->account_type),
            'account_id' => $this->account_type == AccountType::Patient ? $this->patient->id : ($this->account_type == AccountType::Doctor ? $this->doctor->id : null),
            'photo' => $this->photo != null ? asset($this->photo) : null,
            'birth_date' => $this->birth_date,
            'active' => $this->active,
            'phone' => $this->phone,
            'clinic_id' => $this->clinic_id,
            'created_at' => $this->created_at->format('y-m-d'),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }

    public function toLogin()
    {
        $user = $this['user'];

        return [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'account_type' => AccountType::getKey((int)$user['account_type']),
            'photo' => $user['photo'] != null ? asset($user['photo']) : null,
            'birth_date' => $user['birth_date'],
            'active' => $user['active'],
            'phone' => $user['phone'],
            'clinic_id' => $user['clinic_id'],
            'permissions' => $this['permissions']
        ];
    }

    public function toMedicalReport()
    {
        return [
            'id' => $this->id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'photo' => $this->photo ? asset($this->photo) : null,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
        ];
    }
}
