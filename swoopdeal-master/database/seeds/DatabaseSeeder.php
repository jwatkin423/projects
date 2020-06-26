<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $this->call(CatLvlOneSeeder::class);
        $this->call(CatLvlTwoSeeder::class);
        $this->call(CatLvlThreeSeeder::class);
        $this->call(CatLvlFourSeeder::class);
        $this->call(MerchantsSeeder::class);
        $this->call(OffersSeeder::class);
    }
}
