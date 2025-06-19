<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizeAndBedTypeToRoomTypesTable extends Migration
{
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('size')->nullable()->after('max_occupancy');
            $table->string('bed_type')->nullable()->after('size');
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['size', 'bed_type']);
        });
    }
}