<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('collector_assignments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('revenue_types');
        Schema::dropIfExists('payers');
        Schema::dropIfExists('collectors');
    }

    public function down(): void
    {
        // Legacy tables are intentionally not recreated.
    }
};
