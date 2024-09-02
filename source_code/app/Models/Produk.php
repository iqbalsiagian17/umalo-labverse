<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'tipe_barang',
        'stok',
        'masa_berlaku_produk',
        'merk',
        'no_produk_penyedia',
        'unit_pengukuran',
        'jenis_produk',
        'kode_kbki',
        'nilai_tkdn',
        'no_sni',
        'asal_negara',
        'garansi_produk',
        'sni',
        'uji_fungsi',
        'memiliki_svlk',
        'jenis_alat',
        'fungsi',
        'spesifikasi_produk',
        'ramah_lingkungan',
        'komoditas_id',
        'sub_kategori_id',
        'kategori_id',
        'status',
        'nego',
        'harga_ditampilkan',
        'harga_potongan',
        'harga_tayang',
        'link_ekatalog',
    ];


       
    public function getHargaDiskonAttribute()
    {
        // Kembalikan harga_diskon dari pivot atau logika lain
        return $this->pivot ? $this->pivot->harga_diskon : null;
    }


    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function images()
    {
        return $this->hasMany(ProdukImage::class, 'produk_id');
    }
    
    public function produkList()
    {
        return $this->hasMany(ProdukList::class, 'produk_id', 'id');
    }
    public function bigSales()
    {
        return $this->belongsToMany(BigSale::class, 'big_sale_produk', 'produk_id', 'big_sale_id')
                    ->withPivot('harga_diskon')
                    ->withTimestamps();
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'produk_id', 'id');
    }
 
}
