<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    // Schema::table('guest_reservations', function (Blueprint $table) {
    //     $table->string('status')->default('pending')->after('number_of_guest');
    // });
}
public function down()
{
    // Schema::table('guest_reservations', function (Blueprint $table) {
    //     $table->dropColumn('status');
    // });
}

};
