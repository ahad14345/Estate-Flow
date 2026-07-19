<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hydrate the dataset with 30 production-grade transactional nodes
        Purchase::factory()->count(30)->create();
    }
}