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
       Schema::create('payers', function (Blueprint $table) {
    $table->id();
    $table->enum('payer_type',['individual','business']);
    $table->string('full_name')->nullable();
    $table->string('business_name')->nullable();
    $table->string('phone');
    $table->string('email')->nullable();
    $table->string('location');
    $table->string('electoral_area');
    $table->string('property_number')->nullable();
    $table->string('business_type')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payers');
    }
};
