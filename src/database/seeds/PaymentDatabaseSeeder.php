<?php

namespace Payment\System\Database\Seeds;
use Illuminate\Database\Seeder;

class PaymentDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            PaymentMethodsTableSeeder::class,
        ]);
    }
}
