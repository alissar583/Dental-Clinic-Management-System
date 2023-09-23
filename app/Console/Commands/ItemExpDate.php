<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Notifications\ReservationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ItemExpDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:item-exp-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send whatsapp message when the item reaches the minimum quantity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::query()->with(['quantities', 'clinic.admin'])->get();
        foreach ($items as $item) {
            $admin = $item->clinic->admin;
            $quantities = $items->quantities;
            foreach($quantities as $quantity) {
                if($quantity->exp_date <= Carbon::today()->addDays(10) ) {
                    $message = "Dear $admin->first_name, Quantity No. $quantity->id of the product $item->name_en has 10 days to expire";
                    $admin->notify(new ReservationReminder($message));
                }
            }
        }
    }
}
