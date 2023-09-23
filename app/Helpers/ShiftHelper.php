<?php

namespace App\Helpers;

use Carbon\Carbon;

class ShiftHelper
{

    public static function doctorAvailableTimes($shifts, $unavilableTimes, $start, $end)
    {
        $dates = [];
        $currentDate = Carbon::parse($start);

        while ($currentDate <= Carbon::parse($end)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        $newShifts = array();
        foreach ($shifts as $index => $shift) {
            foreach($dates as $date) {
                $dayName = Carbon::createFromFormat('Y-m-d', $date)->format('l');
                if($dayName == $shift['day']['name_en']) {
                    $shifts[$index]['date'] =  $date;
                }
            }
            $check = false;
            foreach ($unavilableTimes as $unavilableTime) {
                $reservationDay = Carbon::createFromFormat('Y-m-d', $unavilableTime['date'])->format('l');
                if ($shift['day']['name_en'] == $reservationDay) {
                    if ($unavilableTime['from'] > $shift['from'] && $unavilableTime['to'] < $shift['to']) {
                        $new1 = [
                            'from' => $shift['from'],
                            'to' => $unavilableTime['from'],
                            'date' => $unavilableTime['date'],
                            'day' => $shift['day']
                        ];
                        if ($new1['from'] < $new1['to']) {
                            array_push($newShifts, $new1);
                            $check = true;
                        }

                        $new2 = [
                            'from' => $unavilableTime['to'],
                            'to' => $shift['to'],
                            'date' => $unavilableTime['date'],
                            'day' => $shift['day']

                        ];

                        if ($new2['from'] < $new2['to']) {
                            array_push($newShifts, $new2);
                            $check = true;
                        }

                        if ($check) {
                            unset($shifts[$index]);
                            break;
                        }
                    } elseif (
                        $unavilableTime['from'] > $shift['from'] &&
                        $unavilableTime['to'] > $shift['to'] &&
                        $unavilableTime['from'] < $shift['to']
                    ) {
                        $new = [
                            'from' => $shift['from'],
                            'to' => $unavilableTime['from'],
                            'date' => $unavilableTime['date'],
                            'day' => $shift['day']
                        ];
                        array_push($newShifts, $new);
                        unset($shifts[$index]);
                        break;
                    } elseif ($shift['from'] == $unavilableTime['from']) {
                        $new = [
                            'from' => $unavilableTime['to'],
                            'to' => $shift['to'],
                            'date' => $unavilableTime['date'],
                            'day' => $shift['day']
                        ];
                        array_push($newShifts, $new);
                        unset($shifts[$index]);
                        break;
                    } elseif ($shift['to'] == $unavilableTime['to']) {
                        $new = [
                            'from' => $shift['from'],
                            'to' => $unavilableTime['from'],
                            'date' => $unavilableTime['date'],
                            'day' => $shift['day']

                        ];
                        array_push($newShifts, $new);
                        unset($shifts[$index]);
                        break;
                    } else {
                        $shifts[$index]['date'] = $unavilableTime['date'];
                    }
                }
            }
        }

        $shifts = array_merge($shifts, $newShifts);

        // If there are new shifts, modify the array and continue iterating
        if (!empty($newShifts)) {
            return self::doctorAvailableTimes($shifts, $unavilableTimes, $start, $end);
        }

        // No new shifts, return the final array
        return $shifts;
    }
}
