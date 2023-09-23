<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addressable', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('country');
            $table->string('area');
            $table->string('street');
            $table->integer('building_number')->nullable();
            $table->integer('floor_number')->nullable();
            $table->string('note')->nullable();
            $table->morphs('addressable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addressable');
    }
};
