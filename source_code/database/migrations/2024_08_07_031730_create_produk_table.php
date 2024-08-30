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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tipe_barang')->nullable();
            $table->integer('stok');
            $table->date('masa_berlaku_produk');
            $table->string('merk')->nullable();
            $table->string('no_produk_penyedia')->nullable();
            $table->enum('unit_pengukuran', ['Set', 'Paket'])->nullable();
            $table->enum('jenis_produk', ['PDN', 'Impor'])->nullable();
            $table->bigInteger('kode_kbki')->nullable();
            $table->decimal('nilai_tkdn', 8, 2)->nullable();
            $table->string('no_sni')->nullable();
            $table->string('asal_negara')->nullable();
            $table->string('garansi_produk')->nullable();
            $table->enum('sni', ['ya', 'tidak'])->nullable();
            $table->string('uji_fungsi')->nullable();
            $table->enum('memiliki_svlk', ['ya', 'tidak'])->nullable();
            $table->string('jenis_alat')->nullable();
            $table->string('fungsi')->nullable();
            $table->longText('spesifikasi_produk');
            $table->enum('status', ['publish', 'arsip'])->default('arsip'); 
            $table->enum('nego', ['ya', 'tidak'])->default('tidak');
            $table->enum('harga_ditampilkan', ['ya', 'tidak']); 
            $table->decimal('harga_potongan', 15, 2)->nullable();
            $table->decimal('harga_tayang', 15, 2);
            $table->string('link_ekatalog');
            $table->timestamps();


            $table->foreignId('komoditas_id')->constrained('komoditas');
            $table->foreignId('sub_kategori_id')->constrained('sub_kategori');
            $table->foreignId('kategori_id')->constrained('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
