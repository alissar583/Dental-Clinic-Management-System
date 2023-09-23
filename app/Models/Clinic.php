<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
   use HasFactory;
   protected $guarded = [];

   public function address()
   {
      return $this->morphOne(Address::class, 'addressable');
   }

   public function workingDays()
   {
      return $this->morphMany(WorkingDay::class, 'workable');
   }

   public function users()
   {
      return $this->hasMany(User::class);
   }

   public function admin()
   {
      return $this->belongsTo(User::class, 'admin_id');
   }
}
