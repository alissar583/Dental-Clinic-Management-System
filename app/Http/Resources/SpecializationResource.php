<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
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
            'name' => app()->getLocale() == 'en' ? $this->name_en : $this->name_ar,
            'certificate' => $this->pivot?->certificate ?? asset($this->pivot?->certificate),
            'doctors' => DoctorResource::collection($this->whenLoaded('doctors'))
        ];
    }
}
