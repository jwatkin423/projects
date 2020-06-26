<?php

use Illuminate\Database\Seeder;


class CatLvlTwoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\CategoriesLevelTwo::class, 40)->create();
    }
}
