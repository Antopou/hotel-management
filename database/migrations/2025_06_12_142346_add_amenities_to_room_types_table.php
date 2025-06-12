<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmenitiesToRoomTypesTable extends Migration
{
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            // Add after 'price_per_night' or any column that exists in your table
            $table->boolean('has_wifi')->default(false)->after('price_per_night');
            $table->boolean('has_tv')->default(false)->after('has_wifi');
            $table->boolean('has_ac')->default(false)->after('has_tv');
            $table->boolean('has_breakfast')->default(false)->after('has_ac');
            $table->boolean('has_parking')->default(false)->after('has_breakfast');
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn([
                'has_wifi',
                'has_tv',
                'has_ac',
                'has_breakfast',
                'has_parking'
            ]);
        });
    }
}
