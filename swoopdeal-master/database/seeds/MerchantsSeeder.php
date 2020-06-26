<?php

use Illuminate\Database\Seeder;


class MerchantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Merchants::class, 100)->create();
    }
}
