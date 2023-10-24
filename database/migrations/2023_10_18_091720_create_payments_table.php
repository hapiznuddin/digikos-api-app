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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('occupant_id', 255);
            $table->foreign('occupant_id')->references('id')->on('occupants')->onDelete('cascade');
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->unsignedBigInteger('rent_id');
            $table->foreign('rent_id')->references('id')->on('rents')->onDelete('cascade');
            $table->integer('price');
            $table->string('status');
            $table->string('checkout_link')->nullable();
            $table->string('checkout_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
