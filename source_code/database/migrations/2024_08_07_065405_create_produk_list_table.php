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
        Schema::create('produk_list', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();


            $table->foreignId('produk_id')->constrained('produk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_list');
    }
};
