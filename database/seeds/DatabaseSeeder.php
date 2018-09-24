<?php

use Illuminate\Database\Seeder;

use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AppUsersSeeder::class,
            PaymentsSeeder::class
        ]);
    }
}
