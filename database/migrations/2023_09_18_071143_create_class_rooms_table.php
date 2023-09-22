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
        Schema::create('class_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_facility');
            $table->foreign('id_facility')->references('id')->on('facilities')->onDelete('cascade');
            $table->string('room_name', 255);
            $table->string('room_size', 255);
            $table->integer('room_price');
            $table->integer('room_deposite');
            $table->string('room_description', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
