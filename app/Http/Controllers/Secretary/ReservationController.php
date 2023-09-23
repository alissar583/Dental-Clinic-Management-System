<?php

namespace App\Http\Controllers\Secretary;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanceleReservationRequest;
use App\Http\Requests\GetAvailableTimesRequest;
use App\Http\Requests\GetReservationsRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\AvailableTimesResource;
use App\Http\Resources\PreviewResource;
use App\Http\Resources\ReservationResource;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Treatement;
use App\Services\PreviewService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService,
        protected PreviewService $previewService
    ) {
    }

    public function index(GetReservationsRequest $request)
    {
        $parameters = [
            'duration' => $request->duration,
            'preview_id'  => $request->preview_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        if ($request->doctor_id) {
            $doctor = Doctor::find($request->doctor_id);
            $result = $this->reservationService
                ->getBy('doctor', $doctor, $request->status, $parameters);
        } elseif ($request->treatement_id)
            $result = $this->reservationService->getBy('treatement', $request->treatement_id, $request->status, $parameters);


        if ($result['success']) {
            $data = ReservationResource::collection($result['data']);
            return ResponseHelper::success($data);
        } else
            return ResponseHelper::error(null, $result['message']);
    }

    public function store(StoreReservationRequest $request, Treatement $treatement)
    {
        $result = $this->reservationService->store($request->validated(), auth()->user(), $treatement);
        if ($result['success'])
            return ResponseHelper::success($result['data']);
        else
            return ResponseHelper::error(null, $result['message']);
    }

    public function update(Reservation $reservation, UpdateReservationRequest $request)
    {

        $this->reservationService->update($reservation, $request->validated());

        return ResponseHelper::success();
    }

    public function cancelReservation(Reservation $reservation, CanceleReservationRequest $request)
    {
        $this->authorize('cancel', $reservation);
        $this->reservationService->cancel($reservation, $request->validated());
        return ResponseHelper::success();
    }


    public function getAvailableTimesByMonth(Doctor $doctor, GetAvailableTimesRequest $request)
    {
        $end = Carbon::parse($request->start)->addDays(6)->format('Y-m-d');
        $result = $this->reservationService->getAvailableTimesByMonht($doctor, $request->start, $end);
        $data = AvailableTimesResource::collection($result);
        // $availableTimes =  collect($data)->groupBy('date');
        return ResponseHelper::success($data, false);
    }

    //get all previews
    public function previews(Request $request)
    {

        if ($request->doctor_id) {
            $doctor = Doctor::findOrFail($request->doctor_id);
            $result = $this->previewService->getDoctorPreviews($doctor->id);
        } else
            $result = $this->previewService->get();

        $data = PreviewResource::collection($result);

        if ($data) {
            return ResponseHelper::success($data);
        } else {
            return ResponseHelper::error(false, $result['message']);
        }
    }
}
