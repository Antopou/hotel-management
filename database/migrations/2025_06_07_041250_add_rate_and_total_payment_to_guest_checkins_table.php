<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateAndTotalPaymentToGuestCheckinsTable extends Migration
{
    public function up()
    {
        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->decimal('rate', 10, 2)->default(0)->after('number_of_guest');
            $table->decimal('total_payment', 12, 2)->default(0)->after('rate');
        });
    }

    public function down()
    {
        Schema::table('guest_checkin', function (Blueprint $table) {
            $table->dropColumn(['rate', 'total_payment']);
        });
    }
}
