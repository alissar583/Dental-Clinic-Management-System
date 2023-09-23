<?php

namespace App\Exports;

use App\Models\Reservation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservationExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $condition;
    protected $user_id;
    protected $start_date;
    protected $end_date;


    function __construct($condition, $user_id, $start_date, $end_date)
    {
        $this->condition = $condition;
        $this->user_id = $user_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $now = Carbon::now();

        $start = $now->startOfWeek(Carbon::SATURDAY)->format('Y-m-d');
        $end = $now->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');

        $result = Reservation::query()->select([
            'date', 'p.name_' . app()->getLocale(), 'payment', 't.cost',
        ])->join('treatements as t', 't.id', 'reservations.treatement_id')
            ->join('patients', 'patients.id', 't.patient_id')
            ->where('patients.id', $this->user_id)
            ->join('doctor_preview as dp', 'dp.id', 't.doctor_preview_id')
            ->join('previews as p', 'p.id', 'dp.preview_id');

        switch ($this->condition) {
            case 'daily':
                $result = $result
                    ->whereDate('date', now()->format('Y-m-d'));
                break;
            case 'weekly':
                $result = $result
                    ->whereBetween('date', [$start,  $end]);
                break;
            case 'monthly':
                $result = $result
                    ->whereMonth('date', $now->month);
                break;
            case 'yearly':
                $result = $result
                    ->whereYear('date', $now->year);
                break;
        }

        if ($this->start_date && $this->end_date) {
            $result = $result
                ->whereBetween('date', [$this->start_date,  $this->end_date]);
        } elseif ($start = $this->start_date) {
            $result = $result
                ->whereDate('date', $start);
        }

        return $result->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Date", "Preview Name", "Paid Amount", "Full Amount"];
    }
}
