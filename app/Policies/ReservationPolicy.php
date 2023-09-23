<?php

namespace App\Policies;

use App\Enums\AccountType;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        if ($user->account_type == 2)
            return $reservation->treatement->doctor->id == $user->id;

        elseif ($user->account_type == 4)
            return $reservation->patient->id == $user->id;

        else return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        if ($user->account_type == 2)
            return Doctor::query()->find($user->id)->reservations()->where('reservations.id', $reservation->id)->exists();
        elseif ($user->account_type == 3)
            return $user->id == $reservation->secretary_id;

        return 0;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        //
    }

    public function cancel(User $user, Reservation $reservation)
    {
        if ($user->account_type == 2)
            return Doctor::query()->find($user->id)->reservations()->where('reservations.id', $reservation->id)->exists();
        elseif ($user->account_type == 3)
            return $user->id == $reservation->secretary_id;

        return 0;
    }
}
