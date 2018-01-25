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


use App\Festival;
use App\Post;

$factory->define(Post::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\es_ES\Text($faker));
    $faker->addProvider(new Faker\Provider\es_ES\Person($faker));
    $faker->addProvider(new Faker\Provider\es_ES\Internet($faker));

    $festival_ids = Festival::all()->map(function (Festival $festival) {
        return $festival->id;
    })->toArray();
    $min = min($festival_ids);
    $max = max($festival_ids);

    $title = $faker->unique()->realText(50);
    return [
        'title' => $title,
        'lead' => $faker->realText(200),
        'body' => $faker->realText(2000),
        'festival_id' => $faker->numberBetween($min, $max),
        'permalink' => str_slug($title),
    ];
});