<?php

namespace App\Http\Resources;

use App\Enums\ReservationStatus;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'note' => $this->note,
            'payment' => $this->payment,
            'date' => $this->date,
            'start_time' => ResponseHelper::convertDate( $this['date'], $this['from']),
            'end_time' => ResponseHelper::convertDate( $this['date'], $this['to']),
            'cancelled_reason' => $this->cancelled_reason,
            'status_name' => app()->getLocale() == 'en' ? $this->status->name_en : $this->status->name_ar,
            'patient' => PatientResource::make($this->whenLoaded('patient')),
            'treatement' => TreatementResource::make($this->whenLoaded('treatement'))
        ];
        return $data;
    }

}
