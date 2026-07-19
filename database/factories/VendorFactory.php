<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $categories = ['Cement', 'Steel', 'Cables', 'Electronics', 'Aggregates', 'Interior', 'Sanitary'];
        $types = ['Manufacturer', 'Distributor', 'Sub-Contractor', 'Importer'];
        
        return [
            'company_name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'biz_reg_no' => 'REG-' . $this->faker->numberBetween(100000, 999999),
            'tax_vat_no' => 'VAT-' . $this->faker->numberBetween(10000000, 99999999),
            'phone' => '+8801' . $this->faker->numberBetween(3, 9) . $this->faker->randomNumber(8, true),
            'email' => $this->faker->unique()->companyEmail(),
            'website' => $this->faker->url(),
            'biz_category' => $this->faker->randomElement($types),
            'mat_category' => $this->faker->randomElement($categories),
            'address' => $this->faker->address(),
            'city' => $this->faker->randomElement(['Dhaka', 'Chattogram', 'Khulna', 'Sylhet', 'Rajshahi']),
            'postal_code' => $this->faker->postcode(),
            'bank_name' => $this->faker->randomElement(['Brac Bank', 'Islami Bank', 'Dutch-Bangla Bank', 'SEBL']),
            'bank_acc_no' => $this->faker->bankAccountNumber(),
            'pay_method' => $this->faker->randomElement(['EFT', 'Cheque', 'Bank Transfer']),
            'pay_terms' => $this->faker->randomElement(['Net 30', 'Net 15', 'COD']),
            'status' => $this->faker->randomElement(['Active', 'Active', 'Inactive']),
            'rating' => $this->faker->numberBetween(3, 5),
            'total_pos' => $this->faker->numberBetween(5, 40),
            'total_po_value' => $this->faker->randomFloat(2, 50000, 7500000),
            'pending_payment' => $this->faker->randomFloat(2, 0, 1200000),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}