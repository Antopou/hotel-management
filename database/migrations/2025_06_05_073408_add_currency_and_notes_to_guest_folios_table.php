<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            $table->string('currency', 10)->default('USD')->after('status');
            $table->text('notes')->nullable()->after('currency');
        });
    }

    public function down()
    {
        Schema::table('guest_folios', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('notes');
        });
    }
};
