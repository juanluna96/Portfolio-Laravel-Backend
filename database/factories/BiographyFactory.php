<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Biography;
use Faker\Generator as Faker;

$factory->define(Biography::class, function (Faker $faker) {
    return [
        'description' => json_encode([
            'en' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'es' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
        ]),
        'stacks_description' => json_encode([
            'en' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'es' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
        ]),
        'about_me' => json_encode([
            'en' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
            'es' => $this->faker->sentence($nbWords = 7, $variableNbWords = true),
        ]),
        'phone_1' => $faker->phoneNumber,
        'phone_2' => $faker->phoneNumber,
        'email_1' => $faker->safeEmail,
        'email_2' => $faker->safeEmail,
        'user_id' => User::all()->random()->id,
    ];
});
