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
        Schema::create('t_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('t_orders')->onDelete('cascade');
            $table->enum('status', ['pending', 'pading', 'paid', 'failed', 'refunded', 'partially_refunded']);
            $table->string('payment_proof');
            $table->timestamp('unpaid_at')->nullable();
            $table->timestamp('pending_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('partially_refunded_at')->nullable();
            $table->boolean('is_viewed_by_admin')->default(false); 
            $table->boolean('is_viewed_by_customer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_payments');
    }
};
