<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $customerTypes = ['Buyer', 'Seller', 'Investor', 'Tenant'];
        $statuses = ['Lead', 'Active', 'Closed'];
        $cities = ['Dhaka', 'Chattogram', 'Sylhet', 'Khulna', 'Rajshahi', 'Barisal', 'Rangpur'];
        $employees = ['Ayesha', 'Rahim', 'Nadia', 'Sajid', 'Mina', 'Tarek', 'Farah'];

        return [
            'customer_code' => 'CUST-' . strtoupper($this->faker->unique()->bothify('###')),
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->numerify('01#########'),
            'national_id' => $this->faker->numerify('##########'),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->randomElement($cities),
            'customer_type' => $this->faker->randomElement($customerTypes),
            'preferred_property_type' => $this->faker->randomElement(['Apartment', 'Villa', 'Office', 'Plot', 'Townhouse']),
            'budget' => $this->faker->numberBetween(500000, 15000000),
            'assigned_employee' => $this->faker->randomElement($employees),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
