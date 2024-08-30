<?php

namespace Database\Seeders;

use App\Models\Komoditas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KomoditasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Komoditas::create([
            'nama' => 'Perlatan Pendidikan',
            'flag' => 'yes',
        ]);
        // Tambahkan lebih banyak komoditas sesuai kebutuhan
    }
}
