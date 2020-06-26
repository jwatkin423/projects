<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\AlertManagement::class, function(Faker\Generator $faker) {
    $number = $faker->numberBetween(0,2);

    $reminders = ['daily', 'weekly', 'monthly'];
    $reminder_settings = ['daily' => '1300', 'weekly' => 'MWF:1300', 'monthly' => '1,15,L:1300'];


    $reminder = $reminders[$number];
    $rs = $reminder_settings[$reminder];

    $sentence = rtrim(lcfirst(str_replace(' ', '', $faker->sentence(3, false))), '.');

    return [
        'job_alert_name' => $sentence,
        'priority' => $faker->numberBetween(1,5),
        'importance' => $faker->numberBetween(1,5),
        'delivery' => 'joe@adrenalads.com,nate@adrenalads.com,josh@adrenalads.com',
        'reminder' => $reminder,
        'reminder_setting' => $rs
    ];

});