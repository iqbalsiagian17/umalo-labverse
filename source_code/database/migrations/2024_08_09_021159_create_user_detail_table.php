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
        Schema::create('user_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // Matching the type of the users.id
            $table->string('no_telepone');
            $table->string('perusahaan');
            $table->date('lahir');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_detail');
    }
};
