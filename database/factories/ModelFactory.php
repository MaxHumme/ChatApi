<?php

use MaxHumme\ChatApi\Infrastructure\Orm\Message;
use MaxHumme\ChatApi\Infrastructure\Orm\User;
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

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'auth_token' => str_random(16),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});

$factory->define(Message::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->realText(250)
    ];
});
