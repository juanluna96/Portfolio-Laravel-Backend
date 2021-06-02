<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->safeEmail,
        'country' => $faker->country,
        'countryCode' => "+" . $faker->numberBetween(0, 9) . $faker->numberBetween(0, 9),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'message' => $faker->paragraph(20),
        'read' => $faker->numberBetween(0, 1)
    ];
});
