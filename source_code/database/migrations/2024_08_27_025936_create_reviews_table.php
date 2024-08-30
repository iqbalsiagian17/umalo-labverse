<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->unsignedInteger('user_id'); // Matching the type of the users.id
            $table->text('content');
            $table->integer('rating');
            $table->json('images')->nullable(); // Storing images as a JSON array
            $table->json('videos')->nullable(); // Storing videos as a JSON array
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
