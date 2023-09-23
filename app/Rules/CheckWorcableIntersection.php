<?php

namespace App\Rules;

use App\Enums\AccountType;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\WorkingDay;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckWorcableIntersection implements ValidationRule
{
    public function __construct(protected $user, public $type = null)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            DB::beginTransaction();
            if ($this->type == 'doctor') {
                WorkingDay::where([
                    'workable_type' => Doctor::class,
                    'workable_id' => $this->user->id
                ])->delete();
            } else
            if (!$this->type) {
                WorkingDay::where([
                    'workable_type' => Clinic::class,
                    'workable_id' => $this->user->id
                ])->delete();
            }

            $myWorkingDays = $this->user?->workingDays()->get();
            if ($this->type == 'doctor') {
                $clinicWorkingDays = $this->user->user->myClinic->workingDays()->get();
            }
            $days = collect($value)->groupBy('day_id');
            foreach ($days as $day) {
                for ($i = 0; $i < count($day) - 1; $i++) {
                    $from1 = $day[$i]['from'];
                    $to1 = $day[$i]['to'];
                    for ($j = $i + 1; $j < count($day); $j++) {
                        $from2 = $day[$j]['from'];
                        $to2 = $day[$j]['to'];
                        if (($from1 >= $from2 && $from1 < $to2) || ($to1 > $from2 && $to1 <= $to2)) {
                            $fail("The time range for day {$day[$i]['day_id']} intersects with another time range.");
                            DB::rollBack();
                        }
                    }
                    if (isset($myWorkingDays) && count($myWorkingDays) > 0) {
                        foreach ($myWorkingDays as $workDay) {
                            if ($workDay->day_id == $day[$i]['day_id']) {
                                if ($day[$i]['from'] > $workDay->to || $day[$i]['to'] < $workDay->from) {
                                } else {
                                    $fail(__('intersection'));
                                    DB::rollBack();
                                }
                            }
                        }
                    }
                    if (isset($clinicWorkingDays)) {
                        if (in_array($day[$i]['day_id'], $clinicWorkingDays->pluck('day_id')->toArray())) {
                            // TODO get the first from time and the last to time  for the clinic 
                            foreach ($clinicWorkingDays as $workDay) {
                                if ($workDay->day_id == $day[$i]['day_id']) {
                                    // if ($day[$i]['from'] > $workDay->to || $day[$i]['from'] < $workDay->from || $day[$i]['to'] > $workDay->to) {
                                    //     $fail(__('the clinic colesd'));
                                    // }
                                }
                            }
                        } else {
                            $fail(__('the clinic colesd'));
                            DB::rollBack();
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
    // }
}
