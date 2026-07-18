<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $sources = ['Website', 'Referral', 'Social Media', 'Walk-in', 'Cold Call'];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];
        $statuses = ['New', 'Contacted', 'Interested', 'Negotiation', 'Converted', 'Lost'];
        $employees = ['Ayesha', 'Rahim', 'Nadia', 'Sajid', 'Mina', 'Tarek', 'Farah'];

        return [
            'lead_code' => 'LEAD-' . strtoupper($this->faker->unique()->bothify('###')),
            'customer_id' => null,
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->numerify('01#########'),
            'lead_source' => $this->faker->randomElement($sources),
            'priority' => $this->faker->randomElement($priorities),
            'status' => $this->faker->randomElement($statuses),
            'assigned_employee' => $this->faker->randomElement($employees),
            'budget' => $this->faker->numberBetween(300000, 12000000),
            'notes' => $this->faker->optional()->sentence(),
            'converted_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
