<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes, \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    // protected $fillable = [];



    public function medicalReport()
    {
        return $this->hasOne(MedicalReport::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function treatements()
    {
        return $this->hasMany(Treatement::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class,Treatement::class);
    }

    public function cancelRequests()
    {
        return $this->hasManyDeepFromRelations($this->reservations(), (new Reservation())->cancelRequests());
    }


}
