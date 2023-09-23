<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'exp_date',
        'quantity',
        'item_id'
    ];


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
