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
        Schema::create('t_bigsales_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bigsale_id')->constrained('t_bigsales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('t_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_bigsales_product');
    }
};
