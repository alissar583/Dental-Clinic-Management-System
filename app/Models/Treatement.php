<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Traits\BelongsToThrough;

class Treatement extends Model
{

    use HasFactory, SoftDeletes, BelongsToThrough;

    protected $fillable = [
        'cost',
        'note',
        'patient_id',
        'doctor_preview_id'
    ];

    public function preview()
    {
        return $this->belongsToThrough(Preview::class, DoctorPreview::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'treatement_id');
    }

    public function doctor()
    {
        return $this->belongsToThrough(Doctor::class, DoctorPreview::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
