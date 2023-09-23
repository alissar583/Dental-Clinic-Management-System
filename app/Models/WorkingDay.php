<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    use HasFactory;
    protected $table = 'workable';
    protected $hidden = ['workable_type', 'workable_id'];

    protected $fillable = [
        'from',
        'to',
        'day_id',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    public function workable(){
        return $this->morphTo();   
    }
}
