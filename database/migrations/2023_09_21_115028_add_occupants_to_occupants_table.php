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
        Schema::table('occupants', function (Blueprint $table) {
            $table->string('name')->after('phone');
            $table->date('date_birth')->after('name');
            $table->string('gender')->after('date_birth');
            $table->string('occupation')->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('occupants', function (Blueprint $table) {
            $table->string('name')->after('phone');
            $table->date('date_birth')->after('name');
            $table->string('gender')->after('date_birth');
            $table->string('occupation')->after('gender');
        });
    }
};
