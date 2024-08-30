<?php

namespace Database\Seeders;

use App\Models\PPN;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PPNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PPN::create([
            'ppn' => 11, // PPN 11%
        ]);
    }
}
