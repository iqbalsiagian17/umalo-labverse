<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubKategorisTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('sub_kategori')) {
            Schema::create('sub_kategori', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->enum('flag', ['yes', 'no'])->default('yes');

                $table->foreignId('kategori_id')->constrained('kategori');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('sub_kategori');
    }
}
