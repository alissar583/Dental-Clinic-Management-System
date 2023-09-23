<?php

namespace App\Models;

use App\Traits\MediaTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Znck\Eloquent\Traits\BelongsToThrough;

class Reservation extends Model implements HasMedia
{
    use InteractsWithMedia,MediaTrait;
    use HasFactory, SoftDeletes, BelongsToThrough;

    protected $fillable = [
        'from',
        'to',
        'note',
        'payment',
        'date',
        'cancelled_reason',
        'treatement_id',
        'status_id',
        'secretary_id',
        'medicines',
        'diagnostics'
    ];

    public $with = ['status'];

    public function status()
    {
        return $this->belongsTo(ReservationStatus::class, 'status_id');
    }
    public function treatement()
    {
        return $this->belongsTo(Treatement::class, 'treatement_id');
    }
    public function preview()
    {
        return $this->belongsToThrough(DoctorPreview::class, Treatement::class);
    }

    public function patient()
    {
        return $this->belongsToThrough(Patient::class, Treatement::class);
    }

    public function secretary(){
        return $this->belongsTo(Secretary::class);
    }

    public function cancelRequests()
    {
        return $this->hasMany(CancellationRequests::class, 'reservation_id');
    }
}
