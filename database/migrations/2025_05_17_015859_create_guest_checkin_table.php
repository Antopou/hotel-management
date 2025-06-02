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
        Schema::create('guest_checkin', function (Blueprint $table) {
            $table->id();
            $table->uuid('checkin_code')->unique();
            $table->uuid('reservation_ref')->nullable();
            $table->uuid('guest_code');
            $table->uuid('room_code');
            $table->datetime('checkin_date');
            $table->datetime('checkout_date');
            $table->datetime('cancelled_date')->nullable();
            $table->integer('rate')->default(0);
            $table->integer('total_payment')->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->tinyInteger('number_of_guest')->default(0);
            $table->boolean('is_checkout')->default(false);
            // $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
            $table->integer('created_by');
            // $table->timestamp('modified_at')->nullable()->useCurrentOnUpdate();
            $table->integer('modified_by')->nullable();
            $table->boolean('is_active')->default(true);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_checkin');
    }
};
