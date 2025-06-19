<?php
// database/migrations/xxxx_xx_xx_xxxxxx_change_size_to_string_in_room_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSizeToStringInRoomTypesTable extends Migration
{
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->string('size')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->integer('size')->nullable()->change();
        });
    }
}