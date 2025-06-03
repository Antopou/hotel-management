<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            if (!Schema::hasColumn('room_types', 'price_per_night')) {
                $table->decimal('price_per_night', 10, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('room_types', 'max_occupancy')) {
                $table->integer('max_occupancy')->default(1)->after('price_per_night');
            }
            if (!Schema::hasColumn('room_types', 'image')) {
                $table->string('image')->nullable()->after('max_occupancy');
            }
            if (!Schema::hasColumn('room_types', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['price_per_night', 'max_occupancy', 'image', 'deleted_at']);
        });
    }
};
