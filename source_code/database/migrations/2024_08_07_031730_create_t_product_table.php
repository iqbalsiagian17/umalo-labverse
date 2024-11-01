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
        Schema::create('t_product', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('stock');
            $table->decimal('price', 15, 2);
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->date('product_expiration_date');
            $table->string('brand')->nullable();
            $table->string('provider_product_number')->nullable();
            $table->enum('measurement_unit', ['Set', 'Package'])->nullable();
            $table->enum('product_type', ['PDN', 'Import'])->nullable();
            $table->bigInteger('kbki_code')->nullable();
            $table->decimal('tkdn_value', 8, 2)->nullable();
            $table->string('sni_number')->nullable();
            $table->string('product_warranty')->nullable();
            $table->enum('sni', ['yes', 'no'])->nullable();
            $table->string('function_test')->nullable();
            $table->enum('has_svlk', ['yes', 'no'])->nullable();
            $table->string('tool_type')->nullable();
            $table->string('function')->nullable();
            $table->longText('product_specifications');
            $table->string('e_catalog_link');
            $table->enum('status', ['publish', 'archive'])->default('archive'); 
            $table->enum('negotiable', ['yes', 'no'])->default('no');
            $table->enum('is_price_displayed', ['yes', 'no']); 

            $table->timestamps();

            $table->foreignId('subcategory_id')->constrained('t_p_sub_category');
            $table->foreignId('category_id')->constrained('t_p_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_product');
    }
};
