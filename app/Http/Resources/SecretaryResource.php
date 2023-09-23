<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  $this->user->id,
            "first_name" =>   $this->user->first_name,
            "last_name" =>  $this->user->last_name,
            "phone" =>   $this->user->phone,
            "photo" =>  $this->user->photo ? asset($this->user->photo) : null,
            "birth_date" =>   $this->user->birth_date,
            "active" =>  $this->user->active,
            "account_type" =>  $this->user->account_type,
            "clinic_id" =>   $this->user->clinic_id,
            "deleted_at" =>   $this->user->deleted_at,
            "created_at" =>   $this->user->created_at,
            "updated_at" =>   $this->user->updated_at,
        ];
    }
}
