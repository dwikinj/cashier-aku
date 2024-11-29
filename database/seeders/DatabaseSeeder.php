<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Member;
use App\Models\Sale;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            MemberSeeder::class,
            SupplierSeeder::class,
            ExpenseSeeder::class,
            PurchaseSeeder::class,
            PurchaseDetailSeeder::class,
            SaleSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
