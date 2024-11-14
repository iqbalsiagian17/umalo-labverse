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
        Schema::create('t_cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('t_users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('t_product')->onDelete('cascade'); // assuming t_product table
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_cart');
    }
};
