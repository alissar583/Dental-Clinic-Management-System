<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Doctor extends Model
{
    use HasFactory, HasRelationships;
    // protected $primaryKey = 'user_id'; 

    protected $fillable = [
        'user_id'
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'doctor_specializations')->withPivot('certificate');
    }

    public function workingDays()
    {
       return $this->morphMany(WorkingDay::class,'workable');
    }

    public function previews(){
        return $this->belongsToMany(Preview::class,'doctor_preview');
    }

    public function reservations()
    {
        return $this->hasManyDeepFromRelations($this->treatements(), (new Treatement())->reservations());
    }

    public function treatements()
    {
        return $this->hasManyThrough(Treatement::class, DoctorPreview::class);
    }

}
