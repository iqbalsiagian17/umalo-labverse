<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_p_sub_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();    
            $table->foreignId('category_id')->constrained('t_p_category')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_p_sub_category');
    }
};
