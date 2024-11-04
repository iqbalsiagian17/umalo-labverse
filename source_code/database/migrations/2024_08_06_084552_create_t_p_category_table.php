<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('t_p_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();    
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_p_category');
    }
};