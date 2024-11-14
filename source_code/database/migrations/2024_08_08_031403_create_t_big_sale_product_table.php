<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_big_sale_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('big_sale_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('discount_price', 15, 2);
            $table->timestamps();

            $table->foreign('big_sale_id')->references('id')->on('t_big_sale')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('t_product')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_big_sale_product');
    }
};
