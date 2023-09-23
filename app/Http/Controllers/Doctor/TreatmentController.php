<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TreatementResource;
use App\Models\Doctor;
use App\Services\TreatementService;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function __construct(protected TreatementService $treatementService){}

}
