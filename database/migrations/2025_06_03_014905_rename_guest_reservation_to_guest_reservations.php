<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::rename('guest_reservation', 'guest_reservations');
}

public function down()
{
    Schema::rename('guest_reservations', 'guest_reservation');
}

};
