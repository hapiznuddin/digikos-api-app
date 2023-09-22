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
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('number_floor', 255)->after('number_room');
            $table->string('room_size', 255)->after('number_floor');
            $table->integer('room_price')->after('room_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('number_floor', 255)->after('number_room');
            $table->string('room_size', 255)->after('number_floor');
            $table->integer('room_price')->after('room_size');
        });
    }
};
