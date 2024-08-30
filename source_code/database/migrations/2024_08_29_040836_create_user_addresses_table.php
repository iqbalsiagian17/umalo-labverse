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
    Schema::create('user_addresses', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('user_id');
        $table->text('alamat');
        $table->string('kota');
        $table->string('provinsi');
        $table->string('kode_pos');
        $table->string('tambahan');
        $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
