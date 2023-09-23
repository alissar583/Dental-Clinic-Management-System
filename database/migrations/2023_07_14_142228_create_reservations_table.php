<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->time('from');
            $table->time('to')->nullable();
            $table->string('note')->nullable();
            $table->integer('payment')->default(0);
            $table->date('date');
            $table->string('cancelled_reason')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('reservation_statuses')->nullOnDelete();
            $table->foreignId('treatement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('secretary_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
