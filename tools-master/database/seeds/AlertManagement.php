<?php

use Illuminate\Database\Seeder;

class AlertManagement extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\AlertManagement::class, 100)->create();
    }
}
