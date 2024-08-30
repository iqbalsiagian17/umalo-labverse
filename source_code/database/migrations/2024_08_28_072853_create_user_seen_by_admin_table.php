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
        Schema::create('user_seen_by_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // Matching the type of the users.id
            $table->unsignedInteger('admin_id'); // Matching the type of the users.id
            $table->timestamps();


            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_seen_by_admin');
    }
};
