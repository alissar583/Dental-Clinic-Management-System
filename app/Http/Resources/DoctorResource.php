<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'specializations' => SpecializationResource::collection($this->whenLoaded('specializations')),
            'workingDays' => $this->whenLoaded('workingDays'),
        ];
    }

    public function paginationInformation($request, $paginated, $default)
    {     
        return $default;
    }
}
