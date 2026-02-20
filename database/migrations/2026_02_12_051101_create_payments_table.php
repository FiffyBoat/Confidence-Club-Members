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
        Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('receipt_number')->unique();
    $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount_paid',12,2);
    $table->enum('payment_method',['cash','bank','momo']);
    $table->foreignId('collector_id')->constrained('users');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
