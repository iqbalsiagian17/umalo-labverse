<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\ProdukImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            'nama' => 'A. Trimble R750 with no Radio, for static only (not upgradable)',
            'tipe_barang' => 'R750',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => 'Trimble R750 with no internal radio for Static Application, • Transport Case • 1.8m USB-C to USB C Cable • Tripod • Tribrach with Antenna Adapter • Zephyr 3 Geodetic GNSS Antenna with 10m Trimble TSC5, c/w: • Rechargeable Battery incl. Battery Door • International Power Supply Kit • Stylus Pen Kit (2x Stylus, 1x Stylus Tether) • Hand Strap • Screen Protectors, Ultra Clear (Package of 15) • USB Data Cable (Mini USB) • Audio Jack Dust Cover',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 388500000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53680766?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk1.png',
            'produk_id' => 1
        ]);


        Produk::create([
            'nama' => 'Laser Scanner',
            'tipe_barang' => 'T10X',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '   1 x X7 Instrument Pack, 1 x Transportation case, 3 x 10.8V battery, 1 x 32gb SDHC Card, 1 x 2.5m Hirose cable, 1 x Tripod, 1 x Backpack, 1 x Trimble T10x Tablet full pack out, 1 x Trimble T10 Battery charger and shoulder strap, 1 x Trimble Perspective software',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 1110000000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53879215?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk2.png',
            'produk_id' => 2
        ]);

        Produk::create([
            'nama' => 'Radio Telemetry for base station',
            'tipe_barang' => 'TDL-450H',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => 'Power Supply, 20m Antenna Cable, Radio Antenna, Antenna Whip - Unity Gain, 430-470MHZ, TDL 450H Radio Kit; 430-470 MHz, 35W, Mast Washer, Cable Programming, NMO to TNC Cable, Transport case, Tripod With Antenna Mast',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 54166667,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53874687?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk3.png',
            'produk_id' => 3
        ]);


        Produk::create([
            'nama' => 'Radio Telemetry for rover',
            'tipe_barang' => 'TDL-450L',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '20m Antenna Cable, Radio Antenna, Antenna Whip - Unity Gain, 430-470MHZ, TDL 450L Radio Kit; 430-470 MHz, 2W, Mast Washer, Cable Programming, NMO to TNC Cable, Transport case',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 122716667,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53877700?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk4.png',
            'produk_id' => 4
        ]);

        Produk::create([
            'nama' => 'Trimble R750 PP Mode For Mobile Static Application with Trimble TSC5 Controller',
            'tipe_barang' => 'R750-PP',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '   1. Material Tabung : Tabung Fiberglass 0.4 dan 0.5 mm 
                                        2. Material Meja Tabung : Plat Besi 0.3 mm 
                                        3. Dimensi Tabung : 
                                        4. Diameter 30 cm, 
                                        5. Tinggi 35 cm
                                        6. Dimensi Meja Tabung : 
                                        7. Diameter atas 40 cm, 
                                        8. Diameter bawah : 70 cm 
                                        9. Kelengkapan : Bola Kacan : 1 Set; Tinta @ 300 gr/btl : 1 botol',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' =>  444000000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/51939935?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk5.png',
            'produk_id' => 5
        ]);

        Produk::create([
            'nama' => 'Pengolahan Data Point Cloud Software',
            'tipe_barang' => 'Trimble Business Center add On Scanning Module',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '   1. Material Tabung : Fiberglass 0.3 mm 
                                        2. Material Kaki dan Rangka : Pipa besi 
                                        3. Dimensi : Ø Diameter 15 cm, Tinggi 40 cm',
            'komoditas_id' => 1,
            'sub_kategori_id' => 3,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 188700000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/48752909?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk6.png',
            'produk_id' => 6
        ]);

        Produk::create([
            'nama' => 'GPS Geodetik RTK System',
            'tipe_barang' => 'R4s RTK System',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Trimble - USA',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'USA',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '2x R4s 1 set for Base and 1 set for Rover - 1 x R4s L1/L2 GNSS Single Receiver Kit with UHF 430-470 MHz 2W TRx - 1 x Charging Kit - 1 x Pole Bracket Accessories for Base Station - 1 x Tripod - 1x Tribrach - 1x antenna adapter Accessories For Rover - 1x 2M Range Pole TSC 5 + Trimble Access GNSS Including with: - WWAN, Worldwide Region - TSC5 Pole Mount Bracker & screws - TSC5/TSC7 Quick Release Pole Mount Clamp with Adjustable Arm - Trimble Access GNSS - General Survey; Perpetual License',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 885000000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/3351141?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk7.png',
            'produk_id' => 7
        ]);

        Produk::create([
            'nama' => 'Tidegauge Portable Water Level Recorder',
            'tipe_barang' => 'RS232',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Valeport - UK',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'UK',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '“FULL SET” Valeport Tidemaster Portable Water Level Recorder c/w : wall mounting bracket and electronics/logger WITH DISPLAY in rugged injection moulded housing with batteries. Supplied with: • Windows based TideMaster Express software • Interface lead to PC • RS232 / USB adaptor • Operating manual • System transit case • 1 bar transducer c/w 20m cable and connector',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 154166667,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53877782?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk8.png',
            'produk_id' => 8
        ]);

        Produk::create([
            'nama' => 'Water Level For Tide Monitoring System',
            'tipe_barang' => 'Valeport VRS 20',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Valeport - UK',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'UK',
            'jenis_produk' => 'Impor',
            'spesifikasi_produk' => '   1. Panjang (saluran air) total : 500 cm
                                        2. Lebar (saluran air) : 10 cm
                                        3. Tinggi (saluran air) : 20 cm 
                                        4. Rangka (saluran air) : Besi dan Plat Besi
                                        5. Material (saluran air) : Plat Fiberglass 0.8 cm
                                        6. Material Bak (saluran air) : Rangka Besi dan 
                                        7. Plat Fiberglass 0.5 cm
                                        8. Material Bak Penampung Air : Rangka Besi 
                                        9. dan Plat Fiberglass
                                        10. Pompa : Pompa Air Type Centrifugal. 
                                        11. Kelengkapan :
                                        12. Bak penampung air (1pc), Meter taraf (1 Pc), 
                                        13. Pintu hambat
                                        14. Terdiri dari : 
                                        15. Tajam (1 Pc), Persegi (1 Pc), Bulat (1 Pc)
                                        16. Ambang, Terdiri dari :
                                        17. Lebar (1 Pc), Persegi Panjang (1 Pc), 
                                        18. 2 sisi bulat (1 Pc), 1 sisi bulat (1 Pc), Tajam (1 Pc)',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 326470588,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/53729571?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk9.png',
            'produk_id' => 9
        ]);


        Produk::create([
            'nama' => 'Mapping Drone',
            'tipe_barang' => 'RX1RII ppk AND Pix4D',
            'stok' => 100,
            'masa_berlaku_produk' => '2035-12-31',
            'merk' => 'Labtek',
            'kode_kbki' => '4826201999',
            'asal_negara' => 'UK',
            'jenis_produk' => 'PDN',
            'spesifikasi_produk' => 'WingtraOne Gen II with RGB Camera 42 MP Sony RX1 RII with Pix4D Software Processing, Consist Of : • WingtraOne GEN II base package 1x • Wingtra UAV, ready to fly (the “WingtraOne GEN II”) • 1x Tablet TabActive 3 including ground • Control Software WingtraPilot • Telemetry Module (2.4 Ghz) • 1x Charging station • 2x Sets of batteries • 1x Carrying sleeve • 1x Carrying case for accessories including basic spare parts (1x spare propeller, 1x anemometer) • 1x Hard Case • 1x TRIMBLE R4s GNSS for BASE STATION',
            'komoditas_id' => 1,
            'sub_kategori_id' => 1,
            'kategori_id' => 1,
            'status' => 'publish',
            'nego' => 'ya',
            'harga_ditampilkan' => 'ya',
            'harga_tayang' => 1509790000,
            'link_ekatalog' => 'https://e-katalog.lkpp.go.id/katalog/produk/detail/72595829?type=general',
        ]);

        ProdukImage::create([
            'gambar' => 'assets/dummy/produk/produk10.png',
            'produk_id' => 10
        ]);
    }
}
