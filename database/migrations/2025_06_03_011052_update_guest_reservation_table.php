<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_reservation', function (Blueprint $table) {
            $table->id();
            $table->uuid('reservation_code')->unique();
            $table->uuid('guest_code');
            $table->uuid('room_code');
            $table->datetime('checkin_date');
            $table->datetime('checkout_date');
            $table->timestamp('cancelled_date')->nullable();
            $table->text('reason')->nullable();
            $table->integer('rate')->default(0);
            $table->integer('total_payment')->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->tinyInteger('number_of_guest')->default(0);
            $table->boolean('is_checkin')->default(false);
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_reservation');
    }
};
