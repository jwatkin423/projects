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

$advCodes = [
    'adlift',
    'adnet',
    'advertise',
    'cnx',
    'ecn',
    'ecn2',
    'nxt',
    'prodege',
    'sevensearch'
];

$campaignCodes = [
    'main',
    'us',
    'usron',
    'de',
    'deron',
    'fr',
    'frron',
    'gb',
    'gbron',
    'de'
];

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

// Level 1 Categories
$factory->define(App\Models\CategoriesLevelOne::class, function (Faker\Generator $faker) {
    return [
        'cat_name' => $faker->word,
        'cat_level' => 1,
        'cat_parent_1_id' => 0,
        'cat_parent_2_id' => 0,
        'cat_parent_3_id' => 0,
        'cat_keywords' => $faker->words(3, true)
    ];

});

// Level 2 Categories
$factory->define(App\Models\CategoriesLevelTwo::class, function (Faker\Generator $faker) {
    $minId = \DB::table('categories')->select('cat_id')->where('cat_level', 1)->min('cat_id');
    $maxId = \DB::table('categories')->select('cat_id')->where('cat_level', 1)->max('cat_id');

    return [
        'cat_name' => $faker->word,
        'cat_level' => 2,
        'cat_parent_1_id' => $faker->numberBetween($minId, $maxId),            // Max ID Level 1
        'cat_parent_2_id' => 0,
        'cat_parent_3_id' => 0,
        'cat_keywords' => $faker->words(3, true)
    ];

});

// Level 3 Categories
$factory->define(App\Models\CategoriesLevelThree::class, function (Faker\Generator $faker) {
    $levelTwoCatsMin = \DB::table('categories')->select('cat_id')->where('cat_level', 2)->min('cat_id');
    $levelTwoCatsMax = \DB::table('categories')->select('cat_id')->where('cat_level', 2)->max('cat_id');

    $catParentTwo = random_int($levelTwoCatsMin, $levelTwoCatsMax);
    $catParentOneRaw = \DB::table('categories')->select('cat_parent_1_id')->where('cat_id', $catParentTwo)->first();

    $catParentOne = $catParentOneRaw->cat_parent_1_id;

    return [
        'cat_name' => $faker->word,
        'cat_level' => 3,
        'cat_parent_1_id' => $catParentOne,
        'cat_parent_2_id' => $catParentTwo,
        'cat_parent_3_id' => 0,
        'cat_keywords' => $faker->words(3, true)
    ];

});


// Level 4 Categories
$factory->define(App\Models\CategoriesLevelFour::class, function (Faker\Generator $faker) {
    $levelThreeCatsMin = \DB::table('categories')->select('cat_id')->where('cat_level', 3)->min('cat_id');
    $levelThreeCatsMax = \DB::table('categories')->select('cat_id')->where('cat_level', 3)->max('cat_id');

    $catParentThree = random_int($levelThreeCatsMin, $levelThreeCatsMax);

    $catParentTwoRaw = \DB::table('categories')->select('cat_parent_2_id')->where('cat_id', $catParentThree)->first();
    $catParentTwo = $catParentTwoRaw->cat_parent_2_id;

    $catParentOneRaw = \DB::table('categories')->select('cat_parent_1_id')->where('cat_id', $catParentTwo)->first();
    $catParentOne = $catParentOneRaw->cat_parent_1_id;

    return [
        'cat_name' => $faker->word,
        'cat_level' => 4,
        'cat_parent_1_id' => $catParentOne,
        'cat_parent_2_id' => $catParentTwo,
        'cat_parent_3_id' => $catParentThree,
        'cat_keywords' => $faker->words(3, true)
    ];

});

$factory->define(App\Models\Merchants::class, function (Faker\Generator $faker) use ($advCodes, $campaignCodes) {

    $company = $faker->company;
    $domain = "http://www.{$company}.com";
    $catMinId = \DB::table('categories')->min('cat_id');
    $catMaxId = \DB::table('categories')->max('cat_id');

    return [
        'cat_id' => random_int($catMinId, $catMaxId),
        'merchant_name' => $company,
        'canonical_domain' => $domain,
        'adv_code' => $faker->randomElement($advCodes),
        'campaign_code' => $faker->randomElement($campaignCodes)
    ];
});

$factory->define(App\Models\Offers::class, function (Faker\Generator $faker) use ($advCodes, $campaignCodes) {
    $merchMinId = \DB::table('merchants')->min('merchant_id');
    $merchMaxId = \DB::table('merchants')->max('merchant_id');

    $catMinId = \DB::table('categories')->min('cat_id');
    $catMaxId = \DB::table('categories')->max('cat_id');

    $catId = random_int($catMinId, $catMaxId);

    $decimal = $faker->randomNumber(2) / 100;
    $price = $faker->numberBetween(1, 1000) . $decimal;
    $discount = $price - ($price * (random_int(1, 10) / 100));

    $merchantId = random_int($merchMinId, $merchMaxId);
    $merchantNameRaw = \DB::table('merchants')->select('merchant_name')->where('merchant_id', $merchantId)->first();
    $merchantName = strtolower(preg_replace('/[^a-zA-Z0-9\/:\.]/', '', $merchantNameRaw->merchant_name));

    $offerName = $faker->word;

    $advCode = $faker->randomElement($advCodes);

    $numberOffer = $faker->randomNumber('5');

    $campaignCode = $faker->randomElement($campaignCodes);

    $rpc = (random_int(1, 100)) / 100;
    $longDescr = $faker->sentence(7);
    $shortDescr = implode(' ', $faker->words(4));
    $offerKeywords = implode(' ', $faker->words(random_int(1, 3)));


    /*$data = [
        'cat_id' => $catId,
        'merchant_id' => $merchantId,
        'offer_name' => $offerName,
        'offer_price' => $price,
        'offer_discount' => $discount,
        'offer_url' => "http://www.{$merchantName}.com/{$numberOffer}/{$offerName}",
        'offer_img' => 'blah',
        'offer_short_desc' => $shortDescr,
        'offer_long_desc' => $longDescr,
        'offer_expiry' => $faker->date('Y-m-d', $faker->dateTimeBetween('-3 months', '+3 months')),
        'offer_keywords' => $offerKeywords,
        'offer_likes' => $faker->randomNumber(4),
        'offer_dislikes' => $faker->randomNumber(2),
        'offer_rpc' => $rpc,
        'adv_code' => $advCode,
        'campaign_code' => $campaignCode,
        'offer_source' => $advCode
    ];

    ddd($data);*/

    return [
        'cat_id' => $catId,
        'merchant_id' => $merchantId,
        'offer_name' => $offerName,
        'offer_price' => $price,
        'offer_discount' => $discount,
        'offer_url' => "http://www.{$merchantName}.com/{$numberOffer}/{$offerName}",
        'offer_img' => 'blah',
        'offer_short_desc' => $shortDescr,
        'offer_long_desc' => $longDescr,
        'offer_expiry' => $faker->date('Y-m-d', $faker->dateTimeBetween('-3 months', '+3 months')),
        'offer_keywords' => $offerKeywords,
        'offer_likes' => $faker->randomNumber(4),
        'offer_dislikes' => $faker->randomNumber(2),
        'offer_rpc' => $rpc,
        'adv_code' => $advCode,
        'campaign_code' => $campaignCode,
        'offer_source' => $advCode
    ];

});