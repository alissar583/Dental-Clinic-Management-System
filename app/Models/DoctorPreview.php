<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPreview extends Model
{
    use HasFactory;
    protected $table = 'doctor_preview';

    public function preview()
    {
        return $this->belongsTo(Preview::class);
    }
}
