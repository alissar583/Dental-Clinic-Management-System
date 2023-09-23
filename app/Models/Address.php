<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addressable';
    protected $hidden = ['addressable_type','addressable_id'];

    protected $fillable = [
        'city',
        'country',
        'area',
        'street',
        'building_number',
        'floor_number',
        'note',
        'addressable_type',
        'addressable_id'
    ];


    public function addressable()
    {
        return $this->morphTo();
    }

}
