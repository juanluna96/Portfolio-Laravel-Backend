<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Biography;
use Faker\Generator as Faker;

$factory->define(Biography::class, function (Faker $faker) {
    return [
        'description_en' => $faker->paragraph(30),
        'description_es' => $faker->paragraph(30),
        'stacks_description_en' => $faker->paragraph(40),
        'stacks_description_es' => $faker->paragraph(40),
        'phone_1' => $faker->phoneNumber,
        'phone_2' => $faker->phoneNumber,
        'email_1' => $faker->safeEmail,
        'email_2' => $faker->safeEmail,
        'user_id' => User::all()->random()->id,
    ];
});
