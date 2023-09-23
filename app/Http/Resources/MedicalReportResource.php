<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->medicalReport->id,
            'oid' => $this->medicalReport->oid,
            'medicine' => $this->medicalReport->medicine,
            'else_illnesses' => $this->medicalReport->else_illnesses,
            'join_date' => $this->medicalReport->created_at,
            'illnesses' => IllnessResource::collection($this->medicalReport->illnesses),
            'user' => (new UserResource($this->whenLoaded('user')))->toMedicalReport(),
            'treatements' =>(new TreatmentCollection($this->whenLoaded('treatements')))->toArray($request)
        ];
    }
    
}
