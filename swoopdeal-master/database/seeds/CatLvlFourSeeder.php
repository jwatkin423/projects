<?php

use Illuminate\Database\Seeder;


class CatLvlFourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\CategoriesLevelFour::class, 400)->create();
    }
}
