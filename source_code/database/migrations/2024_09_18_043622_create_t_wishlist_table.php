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
        Schema::create('t_wishlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); 
            $table->foreignId('Product_id')->constrained('t_product')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('t_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_wishlist');
    }
};
