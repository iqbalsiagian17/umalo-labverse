<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_user_seen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Sesuaikan dengan tipe data id di tabel orders
            $table->unsignedInteger('user_id'); // Matching the type of the users.id
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_user_seen');
    }
};
