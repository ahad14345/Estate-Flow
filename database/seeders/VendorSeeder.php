<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $banglaCorporates = [
            ['name' => 'Bashundhara Cement', 'cat' => 'Cement'],
            ['name' => 'Akij Cement', 'cat' => 'Cement'],
            ['name' => 'GPH Ispat', 'cat' => 'Steel'],
            ['name' => 'BRB Cable', 'cat' => 'Cables'],
            ['name' => 'RFL Group', 'cat' => 'Sanitary'],
            ['name' => 'Walton Enterprise', 'cat' => 'Electronics'],
            ['name' => 'Bengal Steel', 'cat' => 'Steel'],
            ['name' => 'Confidence Cement', 'cat' => 'Cement'],
            ['name' => 'Abul Khair Steel', 'cat' => 'Steel'],
            ['name' => 'BSRM Steels', 'cat' => 'Steel'],
            ['name' => 'Navana Group Logistics', 'cat' => 'Interior'],
            ['name' => 'Mir Cement', 'cat' => 'Cement'],
        ];

        foreach ($banglaCorporates as $corp) {
            Vendor::factory()->create([
                'company_name' => $corp['name'],
                'mat_category' => $corp['cat']
            ]);
        }

        // Complete the remainder to fulfill the 30-item realistic dataset metric
        Vendor::factory()->count(18)->create();
    }
}