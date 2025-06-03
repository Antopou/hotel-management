<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->uuid('guest_code')->unique();
            $table->string('name', 100);
            $table->string('gender', 6)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps(); // handles created_at and updated_at automatically
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void {
        Schema::dropIfExists('guests');
    }
};
