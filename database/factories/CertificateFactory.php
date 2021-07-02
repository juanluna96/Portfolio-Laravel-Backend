<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Certificate;
use Faker\Generator as Faker;

$factory->define(Certificate::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'title' => json_encode([
            'en' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'es' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
        ]),
        'hours' => $faker->numberBetween(25, 56),
        'website' => $faker->company,
        'image' => $faker->image('public/storage/certificates', 640, 480, null, false)
    ];
});
