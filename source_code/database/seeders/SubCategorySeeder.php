<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $CategoryPendidikan1 = Category::where('name', 'Peralatan Pendidikan SMK')->first();

        SubCategory::create([
            'name' => 'Teknologi Kontruksi dan Properti',
            'category_id' => $CategoryPendidikan1->id,
            'slug' => 'Teknologi-Kontruksi-dan-Properti',
        ]);

        SubCategory::create([
            'name' => 'Teknologi Manufaktur dan Rekayasa',
            'category_id' => $CategoryPendidikan1->id,
            'slug' => 'Teknologi-Manufaktur-dan-Rekayasa',
        ]);

        SubCategory::create([
            'name' => 'Teknologi Informasi',
            'category_id' => $CategoryPendidikan1->id,
            'slug' => 'Teknologi-Informasi',
        ]);

        $CategoryPendidikan = Category::where('name', 'Peralatan Pendidikan Sangar Kegiatan Belajar')->first();

        SubCategory::create([
            'name' => 'Peralatan Laboratorium Bahasa',
            'category_id' => $CategoryPendidikan->id,
            'slug' => 'Peralatan-Laboratorium-Bahasa',
        ]);
        
        


        // Tambahkan lebih banyak subCategory sesuai kebutuhan
    }
}
