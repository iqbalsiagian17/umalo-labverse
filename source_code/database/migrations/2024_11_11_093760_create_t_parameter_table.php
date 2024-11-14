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
        Schema::create('t_parameter', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('ecommerce_name')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('address')->nullable();
            $table->string('slogan')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_city')->nullable();
            $table->text('bank_address')->nullable();
            $table->string('director')->nullable();
            $table->string('logo1')->nullable();
            $table->string('logo2')->nullable();
            $table->string('logo3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_parameter');
    }
};
