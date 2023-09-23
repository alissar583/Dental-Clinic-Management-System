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
        Schema::disableForeignKeyConstraints();
        Schema::create('workable', function (Blueprint $table) {
            $table->id();
            $table->time('from')->format('H:i');
            $table->time('to');
            $table->foreignId('day_id')->nullable()->constrained('days')->nullOnDelete();
            $table->morphs('workable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workable');
    }
};
