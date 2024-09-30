<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Cek jika tabel settings kosong sebelum menyimpan data
         if (Setting::count() === 0) {
            Setting::create([
                'company_name' => 'Cashier Aku',
                'company_address' => 'Jl. Kiayi Haji Dahlan',
                'company_phone' => '+6289999999998',
                'member_discount' => 5,
                'logo_path' => 'storage/default/company_logo.png',
                'member_card_path' => 'storage/default/card_member.png',
            ]);
        }
    }
}
