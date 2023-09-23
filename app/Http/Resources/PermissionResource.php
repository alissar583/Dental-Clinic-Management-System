<?php

namespace App\Http\Resources;

use App\Enums\PermissionGroupEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        
        return [
            'id' => $this->id,
            'name' => app()->getLocale() == 'en' ? $this->name : $this->name_ar,
            // 'group' => __('permission.' .PermissionGroupEnum::getKey($this->group)),
        ];
    }
}
