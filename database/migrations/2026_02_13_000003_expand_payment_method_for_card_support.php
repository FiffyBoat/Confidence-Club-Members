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
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::create('payments_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_method', 20);
            $table->foreignId('collector_id')->constrained('users');
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::statement('
            INSERT INTO payments_tmp (id, receipt_number, invoice_id, amount_paid, payment_method, collector_id, received_by, created_at, updated_at)
            SELECT id, receipt_number, invoice_id, amount_paid, payment_method, collector_id, received_by, created_at, updated_at
            FROM payments
        ');

        Schema::drop('payments');
        Schema::rename('payments_tmp', 'payments');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op on rollback to avoid data-loss from enum narrowing.
    }
};
