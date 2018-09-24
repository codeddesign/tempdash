<?php

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\AppUser;

class AppUsersSeeder extends Seeder
{
    /**
     * Seed users
     */
    public function run() {
        $faker = FakerFactory::create();

        // Generate users
        echo "Creating users...\n";

        for ($x = 0; $x <= 1500; $x++)
        {
            try
            {
                AppUser::create([
                    'company' => 'Ternio',
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'department' => 'Marketing',
                    'company_id' => 1,
                    'phone' => $faker->phoneNumber,
                    'email' => $faker->email,
                    'password' => bcrypt('abc123'),
                    'is_verified_by_admin' => true,
                    'is_email_verified' => true,
                    'is_inactive' => (rand(1, 10) % 2) == 0,
                    'role' => 'Admin'
                ]);

                echo "Added ${x} user(s)...\n";
            }
            catch (\Exception $ex)
            {
                // Ignore error
            }
        }
    }
}