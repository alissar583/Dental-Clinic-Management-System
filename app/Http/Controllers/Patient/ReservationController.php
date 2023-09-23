<?php

namespace App\Http\Controllers\Patient;

use App\Exports\ReservationExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelReservationRequest;
use App\Http\Requests\AddMediaToReservationRequest;
use App\Http\Requests\FinancialReportRequest;
use App\Http\Requests\GetReservationsRequest;
use App\Http\Resources\CancelRequestResource;
use App\Http\Resources\ReservationDetailsResource;
use App\Models\CancellationRequests;
use App\Models\Reservation;
use App\Http\Resources\ReservationResource;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\FileHelper;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ) {
    }

    public function index(GetReservationsRequest $request)
    {
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ];

        if ($request->treatement_id)
            $result = $this->reservationService->getBy('treatement', $request->treatement_id, $request->status, $filters);
        if ($result['success']) {
            $data = ReservationDetailsResource::collection($result['data']);
            return ResponseHelper::success($data);
        } else
            return ResponseHelper::error(null, $result['message']);
    }

    public function cancelRequest(Reservation $reservation, CancelReservationRequest $request)
    {
        if ($reservation->cancelRequests->count() > 0)
            return ResponseHelper::error(null, __('The cancellation request has already been sent'));

        if ((now()->format('Y-m-d') == $reservation->date && now()->addHours(3)->format('H:m:s') < $reservation->to))
            return ResponseHelper::error(true, __('Less than 3 hours left'));

        if (now()->format('Y-m-d') < $reservation->date)
            return ResponseHelper::error(true, __('Reservation expired'));


        $this->reservationService->cancelResquest($reservation, $request->validated());
        return ResponseHelper::success([
            'data' => []
        ]);
    }

    public function getPatientCancelRequests()
    {
        $result = $this->reservationService->getPatientCancelRequests(auth()->user()->patient, request()->get('filter'));
        $data = CancelRequestResource::collection($result)->response()->getData();
        return ResponseHelper::success($data);
    }

    public function addMedia(Reservation $reservation, AddMediaToReservationRequest $request)
    {
        $result = $this->reservationService->patientAddMediaToReservation($reservation, $request->validated());
        if (!$result['success']) return ResponseHelper::error(false, $result['message']);

        return ResponseHelper::success([
            'data' => []
        ]);
    }


    public function show(Reservation $reservation)
    {
        if (Auth::user()->can('view', $reservation)) {
            $reservation = $this->reservationService->reservationDetails(Reservation::find($reservation->id));
            $data = ReservationDetailsResource::make($reservation['data']);

            return ResponseHelper::success($data);
        };

        return ResponseHelper::error(null, $reservation['message'] ?? 'this reservation related with another user');
    }

    public function export(FinancialReportRequest $request)
    {
        if (!$request->user_id)
            $user_id = auth()->id();
        $user_id = $request->user_id;

        $fileName = $request->file_name ? $request->file_name : 'financialReport';

        Excel::store(
            new ReservationExport($request->condition, $user_id, $request->start_date, $request->end_date),
            $fileName . '.xlsx',
            'public',
            \Maatwebsite\Excel\Excel::XLSX
        );

        $path = 'storage/' . $fileName . '.xlsx';

        return ResponseHelper::success(asset($path));
    }
}
