<?php

use Illuminate\Database\Seeder;


class CatLvlThreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\CategoriesLevelThree::class, 120)->create();
    }
}
