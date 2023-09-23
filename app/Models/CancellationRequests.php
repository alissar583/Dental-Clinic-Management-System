<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationRequests extends Model
{
    use HasFactory;

    protected $table = 'cancellation_requests';

    protected $fillable = [
        'reservation_id', 
        'reason', 
        'status'
    ];


    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function patient()
    {
        return $this->reservation->treatement->patient;
    }
}
