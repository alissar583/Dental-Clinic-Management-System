<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CancelRequestResource extends JsonResource
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
            'reason' => $this->reason,
            'status' => $this->getStatus($this->status),
            'reservation' => ReservationResource::make($this->whenLoaded('reservation'))
        ];
    }

    public function getStatus($status)
    {
        if($status == null) {
            return 'pending';
        }
        if($status == 1) {
            return 'confirmed';
        }if($status == 0) {
            return 'refused';
        }
    }
}
