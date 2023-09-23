<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Enums\ReservationStatus;
use App\Helpers\ResponseHelper;
use App\Helpers\ShiftHelper;
use App\Models\CancellationRequests;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\Treatement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

/**
 * Class ReservationService.
 */
class ReservationService
{
    public function store(array $data, $secretary, $treatement)
    {
        try {
            $data['secretary_id'] = $secretary->id;
            $data['status_id'] = ReservationStatus::Confirmed;
            $data['treatement_id'] = $treatement->id;
            $from = $data['from'];
            $to = $data['to'] ?? $from->addMiutes(15);

            $doctor = $treatement->doctor;
            $doctorReservations =  $doctor->reservations->where('date', $data['date'])->where('status_id', ReservationStatus::Confirmed);
            $patientReservations = $treatement->patient->reservations->where('date', $data['date'])->where('status_id', ReservationStatus::Confirmed);

            foreach ($patientReservations as $reservation) {
                if (!$this->valueInRange($from, $to, $reservation->from, $reservation->to))
                    //TODO translate
                    return ResponseHelper::error(true, __('Patient has another reservation in this time'));
            }

            foreach ($doctorReservations as $reservation) {
                if (!$this->valueInRange($from, $to, $reservation->from, $reservation->to))
                    return ResponseHelper::error(true, __('Doctor had another reservation in this time'));
            }

            $reservationDay = Carbon::createFromFormat('Y-m-d', $data['date'])->format('l');
            $doctorAvailableTimes = $doctor->workingDays()
                ->whereHas('day', function ($q) use ($reservationDay) {
                    $q->where('name_en', $reservationDay);
                })->get();

            $available = false;
            foreach ($doctorAvailableTimes as $time) {
                $timeFrom = date('H:i', strtotime($time->from));
                $timeTo = date('H:i', strtotime($time->to));

                $available = ($from >= $timeFrom && $to <= $timeTo);
                if ($available)
                    break;
            }

            if (!$available)
                return ResponseHelper::error(true, __('This time is out of doctor working times'));

            $reservation = Reservation::query()->create($data);

            return ResponseHelper::success($reservation, true);
        } catch (\Throwable $th) {
            return ResponseHelper::error();
        }
    }

    private function valueInRange($value1, $value2, $from, $to)
    {
        return ($value1 < $value2 && $value2 <= $from || $value1 >= $to && $value2 > $value1);
    }


    public function cancel(Reservation $reservation, $data)
    {
        $reservation->update([
            'status_id' => ReservationStatus::Cancelled,
            'cancelled_reason' => $data['cancelled_reason']
        ]);
    }

    public function getBy($by, $value, $status, $filters)
    {
        $reservations = [];
        if ($by == 'treatement') {
            $reservations = Treatement::findOrFail($value)->reservations();
        }
        if ($by == 'doctor') {
            $reservations = $value->reservations();
            $reservations = $reservations->with('patient');
            if (auth()->user()->account_type == 3)
                $reservations = $reservations->with('treatement');
        }
        if (isset($filters['duration'])) {
            $now = Carbon::now();
            $start = $now->startOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            $end = $now->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');

            if ($filters['duration'] == 'daily') {
                $reservations = $reservations
                    ->whereDate('date', now()->format('Y-m-d'));
            } elseif ($filters['duration'] == 'weekly') {
                $reservations = $reservations
                    ->whereBetween('date', [$start,  $end]);
            } elseif ($filters['duration'] == 'monthly') {
                $reservations = $reservations
                    ->whereMonth('date', $now->month);
            }
        }

        if (isset($filters['preview_id'])) {
            $preview_id = $filters['preview_id'];
            $reservations = $reservations->whereHas('preview', function ($q) use ($preview_id) {
                $q->where('preview_id', $preview_id);
            });
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $reservations = $reservations
                ->whereBetween('date', [$filters['start_date'],  $filters['end_date']]);
        } elseif (isset($filters['start_date'])) {
            $reservations = $reservations
                ->whereDate('date', $filters['start_date']);
        }

        if ($status == 'Cancelled') {
            $reservations = $reservations->where('status_id', ReservationStatus::Cancelled);
        } else {
            $reservations = $reservations->where('status_id', ReservationStatus::Confirmed);
        }

        if (Auth::user()->account_type == 4) {
            $reservations->with(['treatement' => function ($q) {
                $q->with('doctor')->with('preview');
            }]);
        }

        $reservations = $reservations->get();
        return ResponseHelper::success($reservations, true);
        return ResponseHelper::error(true);
    }


    public function getAvailableTimesByMonht(Doctor $doctor, $start, $end)
    {
        $doctorWorkinDays = $doctor->workingDays->load('day');
        $reservations = Reservation::query()
        ->whereBetween('date', [$start, $end])
        ->where('status_id', ReservationStatus::Confirmed)->get();
        $availableTimes = ShiftHelper::doctorAvailableTimes($doctorWorkinDays->toArray(), $reservations->toArray(), $start,$end);
        return $availableTimes;
    }

    public function reservationDetails($reservation)
    {
        $data = $reservation->load(['media', 'treatement' => function ($q) {
            $q->with('doctor')->with('preview');
        }]);

        if (auth::user()->account_type != AccountType::Patient)
            $data->load('patient');

        return ResponseHelper::success($data, true);
        return ResponseHelper::error(true);
    }

    public function update($reservation, $request)
    {
        if (isset($request['diagnostics']) || isset($request['medicines'])) {
            if ((now()->format('Y-m-d') == $reservation->date && now()->format('H:m:s') < $reservation->to) ||
                now()->format('Y-m-d') < $reservation->date
            )
                return ResponseHelper::error(true, __('can\'t add diagnostics or medicines before the reservation is over'));
        }

        try {
            DB::beginTransaction();
            if(array_key_exists('media', $request)) {
                $reservation->addMultipleMediaFromRequest(['media'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('doctorReservationMedia');
                });
            }
            $reservation->update($request);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(true);
        }

        return ResponseHelper::success([], true);
    }

    public function patientAddMediaToReservation($reservation, $request)
    {
        try {
            DB::beginTransaction();
            $reservation->addMultipleMediaFromRequest(['media'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('patientReservationMedia');
                });
            $reservation->update($request);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(true);
        }

        return ResponseHelper::success([], true);
    }

    public function cancelResquest($reservation, $data)
    {
        CancellationRequests::query()->create([
            'reservation_id' => $reservation->id,
            'reason' => $data['reason']
        ]);
    }

    public function getPatientCancelRequests($patient, $filter)
    {
        $query = $patient->cancelRequests();
        if ($filter != 2) {
            $query->where('status', $filter);
        }
        return $query->paginate(20);
    }

    public function refuseRequest($cancellationRequest)
    {
        $cancellationRequest->update(['status' => 0]);
        return true;
    }

    public function confirmRequest($cancellationRequest)
    {
        $cancellationRequest->status = 1;
        $cancellationRequest->save();
        $data['cancelled_reason'] = $cancellationRequest->reason;
        $this->cancel($cancellationRequest->reservation, $data);
        return true;
    }
}
