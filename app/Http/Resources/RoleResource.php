<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => app()->getLocale() == 'en' ? $this->name : $this->name_ar,
            'created_at' => $this->created_at?->format('y-m-d'),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
