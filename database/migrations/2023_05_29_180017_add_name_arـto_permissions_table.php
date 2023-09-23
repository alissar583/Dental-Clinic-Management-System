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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('name_ar')->after('name');
//            $table->integer('group');
            $table->dropUnique(['name', 'guard_name']);
            // $table->unique(['name', 'guard_name', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->removeColumn('name_ar');
            $table->removeColumn('group');
        });
    }
};
