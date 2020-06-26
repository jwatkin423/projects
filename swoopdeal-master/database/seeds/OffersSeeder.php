<?php

use Illuminate\Database\Seeder;


class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Offers::class, 1000)->create();
    }
}
