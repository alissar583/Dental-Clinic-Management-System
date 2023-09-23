<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'oid',
        'medicine',
        'else_illnesses',
        'patient_id'
    ];

    public function illnesses()
    {
        return $this->belongsToMany(Illness::class, 'illness_medical_reports');
    }

  
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
