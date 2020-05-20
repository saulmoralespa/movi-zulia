<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Driver;
use Faker\Generator as Faker;

$factory->define(Driver::class, function (Faker $faker) {
    return [
        'avatar' => $faker->image('public/storage/img/profile',250,250, 'people', false),
        'name' => "$faker->firstName $faker->lastName",
        'phone' => $faker->phoneNumber,
        'filename' => $faker->image('public/storage/img/profile',400,300, 'transport', false),
        'user_id' => ''
    ];
});
