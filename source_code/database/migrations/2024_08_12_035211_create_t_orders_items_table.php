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
        Schema::create('t_orders_items', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('order_id'); 
            $table->foreignId('product_id')->constrained('t_product')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2); 
            $table->boolean('is_negotiated')->default(false);       
            $table->timestamps();
        
            $table->foreign('order_id')->references('id')->on('t_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

