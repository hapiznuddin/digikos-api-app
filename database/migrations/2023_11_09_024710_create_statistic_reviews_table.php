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
        Schema::create('statistic_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_class_room');
            $table->foreign('id_class_room')->references('id')->on('class_rooms')->onDelete('cascade');
            $table->unsignedInteger('total_testimonies')->default(0);
            $table->unsignedInteger('total_rating')->default(0);
            $table->decimal('average_rating', 5, 1)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_reviews');
    }
};
