<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReservationDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $secretary = $this->secretary?->user;
        $data = [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'note' => $this->note,
            'payment' => $this->payment,
            'date' => $this->date,
            'cancelled_reason' => $this->cancelled_reason,
            'diagnostics' => $this->diagnostics,
            'medicines' => $this->medicines,
            'patient_media' => $this->mediaUrl('patientReservationMedia'),
            'doctor_media' => $this->mediaUrl('doctorReservationMedia'),
            'status_name' => app()->getLocale() == 'en' ? $this->status?->name_en : $this->status?->name_ar,
            'treatement' => TreatementResource::make($this->whenLoaded('treatement')),
            'patient' =>  PatientResource::make($this->whenLoaded('patient')),
            'secretary' => [
                'id' => $secretary?->id,
                'name' => $secretary?->first_name . " " . $secretary?->last_name,
                'phone' => $secretary?->phone,
                'photo' => $secretary?->photo ? asset($secretary->photo) : null
            ],
        ];

        return $data;
    }


    public function toMedicalReport()
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'diagnostics' => $this->diagnostics,
            'medicines' => $this->medicines,
            'patient_media' => $this->mediaUrl('patientReservationMedia'),
            'doctor_media' => $this->mediaUrl('doctorReservationMedia')
        ];
    }
}
