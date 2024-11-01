<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Category::create([
            'name' => 'Peralatan Pendidikan SMK',
            'slug' => 'Peralatan-Pendidikan-SMK',
        ]);

        Category::create([
            'name' => 'Perguruan Tinggi Vokasi',
            'slug' => 'Perguruan-Tinggi-Vokasi',
        ]);

        Category::create([
            'name' => 'Perguruan Tinggi Negeri',
            'slug' => 'Perguruan-Tinggi-Negeri',
        ]);

        Category::create(attributes: [
            'name' => 'Peralatan Pendidikan Sangar Kegiatan Belajar',
            'slug' => 'Peralatan-Pendidikan-Sangar-Kegiatan-Belajar',
        ]);
    }
}
