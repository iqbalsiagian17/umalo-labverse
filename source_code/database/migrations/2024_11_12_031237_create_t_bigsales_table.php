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
        Schema::create('t_bigsales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('banner')->nullable(); // For storing banner image URL or path
            $table->text('modal_image')->nullable(); // New column for modal image on home page
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('discount_amount', 10, 2)->nullable(); // Fixed discount amount
            $table->decimal('discount_percentage', 5, 2)->nullable(); // Discount percentage
            $table->boolean('status')->default(true); // To mark if the bigsale is active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bigsales');
    }
};
