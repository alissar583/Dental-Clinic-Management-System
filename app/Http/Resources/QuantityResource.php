<?php

namespace App\Http\Resources;

use App\Services\QrService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuantityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  parent::toArray($request);
        $data['qr'] = (new QrService)->generate($this->id);
        $data['expired'] =( Carbon::parse($this->exp_date) <  now()) ? true : false;
        return $data;
    }
}
