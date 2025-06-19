<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteToGuestReservationsAndGuestCheckinTables extends Migration
{
    public function up()
    {
        Schema::table('guest_reservations', function (Blueprint $table) {
            $table->text('note')->nullable()->after('status');
        });

        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->text('note')->nullable()->after('is_checkout');
        });
    }

    public function down()
    {
        Schema::table('guest_reservations', function (Blueprint $table) {
            $table->dropColumn('note');
        });

        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}