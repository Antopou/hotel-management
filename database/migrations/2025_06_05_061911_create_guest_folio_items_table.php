<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('guest_folio_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folio_id');    // FK to guest_folios
            $table->string('type');                    // charge or payment
            $table->string('description');             // e.g. Room Charge, Payment, Minibar, etc.
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();   // ref to invoice/receipt/payment id
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->foreign('folio_id')->references('id')->on('guest_folios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_folio_items');
    }
};
