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
        Schema::create('collector_assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('collector_id')->constrained('users');
    $table->string('area_name');
    $table->date('start_date');
    $table->date('end_date')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collector_assignments');
    }
};
