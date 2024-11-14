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
        Schema::create('t_slider', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('button')->nullable(); // Menambahkan kolom tombol
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_slider');
    }
};
