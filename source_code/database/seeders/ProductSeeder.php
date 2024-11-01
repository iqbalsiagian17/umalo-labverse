<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'A. Trimble R750 with no Radio, for static only (not upgradable)',
            'slug' => 'A-Trimble-R750-with-no-Radio-for-static-only-(not-upgradable)',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => 'Trimble R750 with no internal radio for Static Application, • Transport Case • 1.8m USB-C to USB C Cable • Tripod • Tribrach with Antenna Adapter • Zephyr 3 Geodetic GNSS Antenna with 10m Trimble TSC5, c/w: • Rechargeable Battery incl. Battery Door • International Power Supply Kit • Stylus Pen Kit (2x Stylus, 1x Stylus Tether) • Hand Strap • Screen Protectors, Ultra Clear (Package of 15) • USB Data Cable (Mini USB) • Audio Jack Dust Cover',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 388500000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53680766?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product1.png',
            'product_id' => 1
        ]);


        Product::create([
            'name' => 'Laser Scanner',
            'slug' => 'Laser-Scanner',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '   1 x X7 Instrument Pack, 1 x Transportation case, 3 x 10.8V battery, 1 x 32gb SDHC Card, 1 x 2.5m Hirose cable, 1 x Tripod, 1 x Backpack, 1 x Trimble T10x Tablet full pack out, 1 x Trimble T10 Battery charger and shoulder strap, 1 x Trimble Perspective software',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 1110000000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53879215?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product2.png',
            'product_id' => 2
        ]);

        Product::create([
            'name' => 'Radio Telemetry for base station',
            'slug' => 'Radio-Telemetry-for-base-station',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => 'Power Supply, 20m Antenna Cable, Radio Antenna, Antenna Whip - Unity Gain, 430-470MHZ, TDL 450H Radio Kit; 430-470 MHz, 35W, Mast Washer, Cable Programming, NMO to TNC Cable, Transport case, Tripod With Antenna Mast',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 54166667,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53874687?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product3.png',
            'product_id' => 3
        ]);


        Product::create([
            'name' => 'Radio Telemetry for rover',
            'slug' => 'Radio-Telemetry-for-rover',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '20m Antenna Cable, Radio Antenna, Antenna Whip - Unity Gain, 430-470MHZ, TDL 450L Radio Kit; 430-470 MHz, 2W, Mast Washer, Cable Programming, NMO to TNC Cable, Transport case',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 122716667,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53877700?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product4.png',
            'product_id' => 4
        ]);

        Product::create([
            'name' => 'Trimble R750 PP Mode For Mobile Static Application with Trimble TSC5 Controller',
            'slug' => 'Trimble-R750-PP-Mode-For-Mobile-Static-Application-with-Trimble-TSC5-Controller',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '   1. Material Tabung : Tabung Fiberglass 0.4 dan 0.5 mm 
                                        2. Material Meja Tabung : Plat Besi 0.3 mm 
                                        3. Dimensi Tabung : 
                                        4. Diameter 30 cm, 
                                        5. Tinggi 35 cm
                                        6. Dimensi Meja Tabung : 
                                        7. Diameter atas 40 cm, 
                                        8. Diameter bawah : 70 cm 
                                        9. Kelengkapan : Bola Kacan : 1 Set; Tinta @ 300 gr/btl : 1 botol',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' =>  444000000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/51939935?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product5.png',
            'product_id' => 5
        ]);

        Product::create([
            'name' => 'Pengolahan Data Point Cloud Software',
            'slug' => 'Pengolahan-Data-Point-Cloud-Software',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '   1. Material Tabung : Fiberglass 0.3 mm 
                                        2. Material Kaki dan Rangka : Pipa besi 
                                        3. Dimensi : Ø Diameter 15 cm, Tinggi 40 cm',
            'subcategory_id' => 3,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 188700000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/48752909?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product6.png',
            'product_id' => 6
        ]);

        Product::create([
            'name' => 'GPS Geodetik RTK System',
            'slug' => 'GPS-Geodetik-RTK-System',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Trimble - USA',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '2x R4s 1 set for Base and 1 set for Rover - 1 x R4s L1/L2 GNSS Single Receiver Kit with UHF 430-470 MHz 2W TRx - 1 x Charging Kit - 1 x Pole Bracket Accessories for Base Station - 1 x Tripod - 1x Tribrach - 1x antenna adapter Accessories For Rover - 1x 2M Range Pole TSC 5 + Trimble Access GNSS Including with: - WWAN, Worldwide Region - TSC5 Pole Mount Bracker & screws - TSC5/TSC7 Quick Release Pole Mount Clamp with Adjustable Arm - Trimble Access GNSS - General Survey; Perpetual License',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 885000000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/3351141?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product7.png',
            'product_id' => 7
        ]);

        Product::create([
            'name' => 'Tidegauge Portable Water Level Recorder',
            'slug' => 'Tidegauge-Portable-Water-Level-Recorder',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Valeport - UK',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '“FULL SET” Valeport Tidemaster Portable Water Level Recorder c/w : wall mounting bracket and electronics/logger WITH DISPLAY in rugged injection moulded housing with batteries. Supplied with: • Windows based TideMaster Express software • Interface lead to PC • RS232 / USB adaptor • Operating manual • System transit case • 1 bar transducer c/w 20m cable and connector',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 154166667,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53877782?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product8.png',
            'product_id' => 8
        ]);

        Product::create([
            'name' => 'Water Level For Tide Monitoring System',
            'slug' => 'Water-Level-For-Tide-Monitoring-System',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Valeport - UK',
            'kbki_code' => '4826201999',
            'product_type' => 'Import',
            'product_specifications' => '   1. Panjang (saluran air) total : 500 cm
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
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 326470588,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/53729571?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product9.png',
            'product_id' => 9
        ]);


        Product::create([
            'name' => 'Mapping Drone',
            'slug' => 'Mapping-Drone',
            'stock' => 100,
            'product_expiration_date' => '2035-12-31',
            'brand' => 'Labtek',
            'kbki_code' => '4826201999',
            'product_type' => 'PDN',
            'product_specifications' => 'WingtraOne Gen II with RGB Camera 42 MP Sony RX1 RII with Pix4D Software Processing, Consist Of : • WingtraOne GEN II base package 1x • Wingtra UAV, ready to fly (the “WingtraOne GEN II”) • 1x Tablet TabActive 3 including ground • Control Software WingtraPilot • Telemetry Module (2.4 Ghz) • 1x Charging station • 2x Sets of batteries • 1x Carrying sleeve • 1x Carrying case for accessories including basic spare parts (1x spare propeller, 1x anemometer) • 1x Hard Case • 1x TRIMBLE R4s GNSS for BASE STATION',
            'subcategory_id' => 1,
            'category_id' => 1,
            'status' => 'publish',
            'is_price_displayed' => 'yes',
            'price' => 1509790000,
            'e_catalog_link' => 'https://e-katalog.lkpp.go.id/katalog/Product/detail/72595829?type=general',
        ]);

        ProductImage::create([
            'images' => 'assets/dummy/Product/Product10.png',
            'product_id' => 10
        ]);
    }
}
