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
        Schema::create('occupants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id', 255);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('phone', 20);
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupants');
    }
};
