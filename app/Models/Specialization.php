<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specializations')->withPivot('certificate');
    }
}
