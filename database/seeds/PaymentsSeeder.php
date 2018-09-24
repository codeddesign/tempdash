<?php

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class PaymentsSeeder extends Seeder
{
    /**
     * Seed payments
     */
    public function run() {

        $faker = FakerFactory::create();

        echo "Seedings payments...\n";

        // Generate payment records
        for ($x = 0; $x <= 1500; $x++)
        {
            try {
                Payment::create([
                    'payout_method' => rand(0,1) == 0 ? 'Wire Transfer' : 'ACH',
                    'pay_schedule' => rand(0,1) == 0 ? 'Daily, Automatic' : 'Daily, Manual',
                    'pay_period_start' => $faker->date('Y-m-d'),
                    'pay_period_end' => $faker->date('Y-m-d'),
                    'amount' => $faker->numberBetween(20000, 200000),
                    'company_id' => 1
                ]);
            } catch (\Exception $ex) {
                // Ignore error
            }
        }
    }
}