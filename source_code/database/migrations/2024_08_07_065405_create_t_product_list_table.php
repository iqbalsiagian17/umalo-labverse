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
        Schema::create('t_product_list', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('specifications')->nullable();
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();

            $table->foreignId('product_id')->constrained('t_product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_product_list');
    }
};
