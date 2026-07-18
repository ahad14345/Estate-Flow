<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowUpFactory extends Factory
{
    protected $model = FollowUp::class;

    public function definition(): array
    {
        $types = ['Call', 'Meeting', 'Email', 'Site Visit'];
        $statuses = ['Pending', 'Completed'];
        $reminders = ['Pending', 'Sent', 'Completed'];

        return [
            'customer_id' => Customer::query()->inRandomOrder()->value('id'),
            'lead_id' => Lead::query()->inRandomOrder()->value('id'),
            'follow_up_type' => $this->faker->randomElement($types),
            'subject' => $this->faker->sentence(4),
            'notes' => $this->faker->optional()->sentence(),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+12 days'),
            'reminder_status' => $this->faker->randomElement($reminders),
            'status' => $this->faker->randomElement($statuses),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
