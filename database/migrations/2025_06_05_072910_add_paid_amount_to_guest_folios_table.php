<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            $table->decimal('paid_amount', 12, 2)->default(0)->after('total_amount');
        });
    }

    public function down()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};

