<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'position' => json_encode([
            'en' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'es' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
        ]),
        'image' => $faker->image('public/storage/companies', 640, 480, null, false)
    ];
});
