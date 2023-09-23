<?php

namespace App\Observers;

use App\Models\Quantity;
use App\Notifications\ReservationReminder;

class QuantityObserver
{
    public function updated(Quantity $quantity): void
    {
        if ($quantity->quantity == 0) {
            $quantity->delete();
        }
        $item = $quantity->item;
        $items = $item->quantities->where('exp_date', '>', now())->sum('quantity');
        if ($items <= $quantity->item->minimum_quantity) {
            $user = $quantity->item->clinic->admin;
            $message = "Dear $user->first_name, Item $item->name_ar  has reached the minimum limit, please request a new quantity, Minimum Qauntity: $item->minimum_quantity , Current Quantity: $items";
            $user->notify(new ReservationReminder($message));
        }
    }
}
