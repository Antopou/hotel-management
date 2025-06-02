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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_type_code')->unique();
            $table->string('name', 50);
            $table->string('description', 255)->nullable();
            // $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
            $table->integer('created_by');
            // $table->timestamp('modified_at')->nullable()->useCurrentOnUpdate();
            $table->integer('modified_by')->nullable();
            $table->boolean('is_active')->default(true);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
