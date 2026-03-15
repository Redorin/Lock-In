<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('building');
            $table->integer('capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->string('image')->nullable(); // for future image uploads
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_spaces');
    }
};