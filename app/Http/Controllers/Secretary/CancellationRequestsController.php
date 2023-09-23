<?php

namespace App\Http\Controllers\Secretary;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CancelRequestResource;
use App\Models\CancellationRequests;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class CancellationRequestsController extends Controller
{
    public function __construct(protected ReservationService $reservationService)
    {
    }


    public function refuseRequest(CancellationRequests $cancellationRequest)
    {
        $this->reservationService->refuseRequest($cancellationRequest);
        return ResponseHelper::success([]);
    }

    public function confirmRequest(CancellationRequests $cancellationRequest)
    {
        $this->reservationService->confirmRequest($cancellationRequest);
        return ResponseHelper::success([]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:0,1'
        ]);

        $requests = CancellationRequests::query()
            ->where('status', $request->status)
            ->with('reservation', function ($query) {
                $query->with('patient');
            })->get();

        $data = CancelRequestResource::collection($requests);
        return ResponseHelper::success($data);
    }
}
