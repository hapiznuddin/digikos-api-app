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
        Schema::create('class_room_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_class_room');
            $table->foreign('id_class_room')->references('id')->on('class_rooms')->onDelete('cascade');
            $table->string('original_name', 255);
            $table->string('path', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_room_images');
    }
};
