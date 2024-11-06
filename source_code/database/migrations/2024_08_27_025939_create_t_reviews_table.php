<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('user_id'); 
            $table->unsignedBigInteger('order_id'); 
            $table->text('content');
            $table->integer('rating');
            $table->json('images')->nullable(); // Storing images as a JSON array
            $table->json('videos')->nullable(); // Storing videos as a JSON array
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('t_product')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('t_users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('t_orders')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('t_reviews'); // Corrected table name
    }
};
