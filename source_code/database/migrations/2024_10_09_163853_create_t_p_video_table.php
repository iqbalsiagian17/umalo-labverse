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
        Schema::create('t_p_video', function (Blueprint $table) {
            $table->id();
            $table->string('video');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
    
            // Relasi foreign key ke tabel t_product
            $table->foreign('product_id')->references('id')->on('t_product')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_p_video');
    }
};
