<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Znck\Eloquent\Traits\BelongsToThrough;

class Item extends Model
{
    use HasFactory, BelongsToThrough;

    protected $fillable = [
        'name_en',
        'name_ar',
        'minimum_quantity',
        'price',
        'note',
        'category_id'
    ];

    public function quantities()
    {
        return $this->hasMany(Quantity::class, 'item_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function clinic()
    {
        return $this->belongsToThrough(Clinic::class, Category::class);
    }
}
