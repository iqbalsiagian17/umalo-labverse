<?php

namespace Database\Seeders;

use App\Models\TParameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TParameter::create([
            'company_name' => 'PT Arkamaya Guna Saharsa',
            'ecommerce_name' => 'Labverse',
            'email1' => 'info@labtek.id',
            'email2' => 'sales@labtek.id',
            'telephone_number' => '(021) 2204 3144',
            'whatsapp_number' => '(021) 2204 3144',
            'address' => 'Jl. Matraman Raya No.148, RT.1/RW.4, Kab. Manggis, Kec. Matraman, Kota Jakarta Timur, DKI Jakarta 13150 (Ruko Mitra Matraman A2 No.3)',
            'slogan' => 'Level-Up Your Output With Labverse',
            'account_name' => 'PT. Arkamaya Guna Saharsa',
            'bank_name' => 'Bank Mandiri',
            'account_number' => '121-00-002881-1',
            'bank_city' => 'Kebon Sirih',
            'bank_address' => 'Jl. Tanah Abang Timur No. 1, RT.2/RW.3, Gambir, Central Jakarta City, Jakarta 10110',
            'director' => 'Agustina Panjaitan',
            'logo1' => 'assets/images/logo-nobg.png',
            'logo2' => 'assets/images/AGS-logo.png',
            'logo3' => 'storage/logos/sample_logo3.png',
        ]);
    }
}
