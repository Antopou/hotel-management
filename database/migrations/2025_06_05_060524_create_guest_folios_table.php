<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestFoliosTable extends Migration
{
    public function up()
    {
        Schema::create('guest_folios', function (Blueprint $table) {
            $table->id();
            $table->uuid('folio_code')->unique();
            $table->string('reservation_code')->nullable(); // Link to reservation
            $table->string('checkin_code')->nullable();     // Link to check-in (if you use checkin records)
            $table->string('guest_code');
            $table->decimal('total_amount', 10, 2)->default(0); // Grand total
            $table->decimal('amount_paid', 10, 2)->default(0);  // Amount received
            $table->decimal('balance', 10, 2)->default(0);      // Remaining balance
            $table->string('status')->default('open'); // open, closed, cancelled
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_folios');
    }
}
