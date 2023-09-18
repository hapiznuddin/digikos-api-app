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
        Schema::create('facilities', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->boolean('ac');
            $table->boolean('kasur');
            $table->boolean('lemari');
            $table->boolean('meja');
            $table->boolean('wifi');
            $table->boolean('km_luar');
            $table->boolean('km_dalam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
