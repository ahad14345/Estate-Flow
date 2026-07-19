<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        $vendors = ['ABC Cement Ltd.', 'Bashundhara Cement', 'Akij Cement', 'RFL Plastics', 'Walton Industries', 'Bengal Steel', 'BRB Cables', 'GPH Ispat Ltd.', 'Anwar Galvanizing'];
        $contractors = ['Mirza Engineers', 'Ahad Construction Corp', 'Nabil Structural Builders Ltd.', null];
        $projects = ['EstateFlow Luxury Heights', 'Mirza Commercial Park', 'KUET Student Residence Plaza', 'Khulna IT Incubation Center'];
        
        $items = [
            'Construction Materials' => ['Portland Composite Cement', 'Deformed Reinforcement Steel Rods B500DWR', 'Coarse Sand Aggregate Trucks'],
            'Electrical Materials' => ['BRB 1.5rm Flexible Copper Fire Bypass Wire', 'Walton 9W LED Eco Bulbs', 'Industrial Circuit Breakers 3-Phase'],
            'Plumbing Materials' => ['RFL 4-inch PVC Wastewater Pipe Line', 'Chrome Finish Concealed Stop Cocks', 'Water Supply Pumping Motor 2HP']
        ];

        $cat = $this->faker->randomElement(array_keys($items));
        $itemName = $this->faker->randomElement($items[$cat]);
        
        $quantity = $this->faker->numberBetween(5, 120);
        $unitPrice = $this->faker->randomElement([450.00, 850.00, 1200.00, 5200.00]);
        $tax = $this->faker->randomElement([50.00, 150.00, 400.00, 0.00]);
        $discount = $this->faker->randomElement([0.00, 100.00, 250.00]);

        return [
            'purchase_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'expected_delivery_date' => Carbon::now()->addDays($this->faker->numberBetween(4, 20))->format('Y-m-d'),
            'vendor_name' => $this->faker->randomElement($vendors),
            'contractor_name' => $this->faker->randomElement($contractors),
            'project_name' => $this->faker->randomElement($projects),
            'category' => $cat,
            'item_name' => $itemName,
            'item_description' => $this->faker->sentence(),
            'quantity' => $quantity,
            'unit' => $this->faker->randomElement(['Bag', 'Piece', 'Ton', 'Kg']),
            'unit_price' => $unitPrice,
            'discount' => $discount,
            'tax' => $tax,
            'payment_method' => $this->faker->randomElement(['Cash', 'Bank Transfer', 'Cheque', 'bKash', 'Nagad']),
            'payment_status' => $this->faker->randomElement(['Paid', 'Partial', 'Unpaid']),
            'purchase_status' => $this->faker->randomElement(['Pending', 'Ordered', 'Delivered']),
            'remarks' => $this->faker->sentence(),
            'created_by' => $this->faker->randomElement(['Ahad Al Nabil', 'System Operator', 'Procurement Officer Manager'])
        ];
    }
}