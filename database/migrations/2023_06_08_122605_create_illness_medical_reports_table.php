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
        Schema::create('illness_medical_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('illness_id')->constrained('illnesses')->cascadeOnDelete();
            $table->foreignId('medical_report_id')->constrained('medical_reports')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illness_medical_reports');
    }
};
