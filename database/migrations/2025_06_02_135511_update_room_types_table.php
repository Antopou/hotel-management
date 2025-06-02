<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->decimal('price_per_night', 10, 2)->default(0)->after('description');
            $table->integer('max_occupancy')->default(1)->after('price_per_night');
            $table->string('image')->nullable()->after('max_occupancy');
            $table->softDeletes(); // Adds deleted_at column
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['price_per_night', 'max_occupancy', 'image', 'deleted_at']);
        });
    }
};
