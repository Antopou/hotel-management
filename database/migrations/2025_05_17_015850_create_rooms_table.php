<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_code')->unique();
            $table->uuid('room_type_code');
            $table->string('name');
            $table->string('description')->nullable();
            // $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
            $table->integer('created_by');
            // $table->timestamp('modified_at')->nullable()->useCurrentOnUpdate();
            $table->integer('modified_by')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('room_type_code')->references('room_type_code')->on('room_types');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
