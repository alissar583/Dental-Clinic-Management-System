<?php

namespace App\Http\Controllers\Doctor;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanceleReservationRequest;
use App\Http\Requests\GetDoctorReservationsRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationDetailsResource;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function getMyReservations(GetDoctorReservationsRequest $request)
    {
        $parameters = [
            'duration' => $request->duration,
            'preview_id'  => $request->preview_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        $reservations = $this->reservationService->getBy('doctor', auth()->user()->doctor, $request->status, $parameters);
        $data = ReservationResource::collection($reservations['data']);

        return ResponseHelper::success($data);
    }

    public function show(Reservation $reservation)
    {

        if (Auth::user()->can('view', $reservation)) {
            $reservation = $this->reservationService->reservationDetails($reservation);
            $data = ReservationDetailsResource::make($reservation['data']);

            return ResponseHelper::success($data);
        };

        return ResponseHelper::error(null, $reservation['message'] ?? 'this reservation related with another user');
    }

    public function update(Reservation $reservation, UpdateReservationRequest $request)
    {
        $result = $this->reservationService->update($reservation, $request->validated());
        if (!$result['success']) return ResponseHelper::error(false, $result['message']);

        return ResponseHelper::success(['data' => []]);
    }


    public function cancelReservation(Reservation $reservation, CanceleReservationRequest $request)
    {
        $this->authorize('cancel', $reservation);
        $this->reservationService->cancel($reservation, $request->validated());
        return ResponseHelper::success();
    }
}
