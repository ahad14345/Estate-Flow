<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerActivity;
use App\Models\CustomerPropertyInterest;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory(30)->create()->each(function (Customer $customer) {
            CustomerPropertyInterest::create([
                'customer_id' => $customer->id,
                'property_name' => $customer->preferred_property_type . ' in ' . $customer->city,
                'property_reference' => 'PROP-' . rand(100, 999),
                'interest_level' => ['Low', 'Medium', 'High'][rand(0, 2)],
                'visit_date' => now()->addDays(rand(1, 10)),
                'remarks' => 'Interested in premium properties with flexible payment options.',
            ]);

            CustomerActivity::create([
                'customer_id' => $customer->id,
                'activity_type' => 'Follow-up',
                'subject' => 'Profile updated',
                'description' => 'Customer profile reviewed and updated with latest notes.',
                'performed_by' => ['Ayesha', 'Rahim', 'Nadia', 'Sajid'][rand(0, 3)],
                'occurred_at' => now()->subDays(rand(1, 7)),
            ]);
        });

        Lead::factory(20)->create();
        FollowUp::factory(15)->create();
    }
}
