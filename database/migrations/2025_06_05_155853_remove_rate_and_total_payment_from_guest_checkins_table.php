<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->dropColumn(['rate', 'total_payment']);
        });
    }

    public function down(): void
    {
        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->decimal('rate', 10, 2)->nullable(); // Set nullable for safety on rollback
            $table->decimal('total_payment', 10, 2)->nullable();
        });
    }
};
