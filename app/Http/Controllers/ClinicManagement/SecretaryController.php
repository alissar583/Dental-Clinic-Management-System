<?php

namespace App\Http\Controllers\ClinicManagement;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\GetSecretaryResource;
use App\Http\Resources\SecretaryResource;
use App\Models\Secretary;
use Illuminate\Http\Request;

class SecretaryController extends Controller
{
    public function index()
    {
        $secretaries = Secretary::all()->load('user');
        $data = SecretaryResource::collection($secretaries);
        return ResponseHelper::success($data);
    }
}
