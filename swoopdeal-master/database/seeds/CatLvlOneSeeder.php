<?php

use Illuminate\Database\Seeder;


class CatLvlOneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\CategoriesLevelOne::class, 15)->create();
    }
}
