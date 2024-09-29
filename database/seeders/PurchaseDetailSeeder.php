<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua purchases dan buat setidaknya satu PurchaseDetail untuk setiap purchase
        $purchases = Purchase::all();

        foreach ($purchases as $purchase) {
            // Setiap purchase akan memiliki setidaknya satu detail
            PurchaseDetail::factory()->create([
                'purchase_id' => $purchase->id,
            ]);

            // Opsional: Tambahkan lebih banyak purchase details (misalnya 2-5) untuk setiap purchase
            $additionalDetails = rand(1, 3); // Buat 1 hingga 3 detail tambahan secara acak
            PurchaseDetail::factory($additionalDetails)->create([
                'purchase_id' => $purchase->id,
            ]);
        }
    }
}
