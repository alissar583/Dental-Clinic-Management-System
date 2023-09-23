<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatementResource extends JsonResource
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
            'cost' => $this->cost,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'preview_id' => $this->preview->id,
            'preview_name' => app()->getLocale() == 'ar' ? $this->preview->name_ar : $this->preview->name_en,
            'doctor_id' => $this->doctor->user->id,
            'doctor_first_name' => $this->doctor->user->first_name,
            'doctor_last_name' => $this->doctor->user->last_name,
            'doctor_phone' => $this->doctor->user->phone,
            'doctor_photo' => $this->doctor->user->photo ? asset($this->doctor->user->photo) : null,
            'reservations' => ReservationResource::collection($this->whenLoaded('reservations'))
        ];
    }

    public function toMedicalReport($request){
        return [
            'preview_id' => $this->preview->id,
            'preview_name' => app()->getLocale() == 'ar' ? $this->preview->name_ar : $this->preview->name_en,
            'preview_cost' => $this->cost,
            'doctor_id' => $this->doctor->user->id,
            'doctor_first_name' => $this->doctor->user->first_name,
            'doctor_last_name' => $this->doctor->user->last_name,
            'doctor_phone' => $this->doctor->user->phone,
            'doctor_photo' => $this->doctor->user->photo ? asset($this->doctor->user->photo) : null,
            'reservations' => $this->reservations
        ];
    }
}
