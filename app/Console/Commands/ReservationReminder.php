<?php

namespace App\Console\Commands;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Notifications\ReservationReminder as NotificationsReservationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReservationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reservation-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send whatsapp message to patiets befor 24h from reservation date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reservations = Reservation::query()
        ->where('status_id', ReservationStatus::Confirmed)
        ->whereDate('date', Carbon::tomorrow())
        ->with('patient.user')->get();
        foreach($reservations as $reservation) {
            $user = $reservation->patient->user;
            $message = "Dear $user->first_name, Reminder for your reservation appointment tomorrow: $reservation->date , from: $reservation->from, to: $reservation->to";
            $user->notify(new NotificationsReservationReminder($message));
        }
    }
}
