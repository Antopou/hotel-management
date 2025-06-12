<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Convert all existing statuses to lowercase
        DB::statement("UPDATE rooms SET status = LOWER(status)");
    }

    public function down()
    {
        // Can't revert to previous values, but you can leave this empty or set to a default
        // DB::statement("UPDATE rooms SET status = 'Available'"); // example
    }
};
