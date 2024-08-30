<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBigSaleProdukTable extends Migration
{
    public function up()
    {
        // Drop table if it exists to avoid "table already exists" error
        Schema::dropIfExists('big_sale_produk');

        Schema::create('big_sale_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('big_sale_id');
            $table->unsignedBigInteger('produk_id');
            $table->decimal('harga_diskon', 15, 2);
            $table->timestamps();

            $table->foreign('big_sale_id')->references('id')->on('big_sale')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('big_sale_produk');
    }
}