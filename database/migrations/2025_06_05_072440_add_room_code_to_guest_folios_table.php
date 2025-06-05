<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            // Add the room_code column. Adjust length/type as needed.
            $table->string('room_code')->nullable()->after('guest_code');
        });
    }

    public function down()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            $table->dropColumn('room_code');
        });
    }
};
