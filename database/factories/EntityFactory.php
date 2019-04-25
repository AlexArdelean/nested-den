<?php

use Faker\Generator as Faker;

$factory->define(App\Entity::class, function (Faker $faker) {
    return [
        'entity_type' => $faker->entity_type,
    ];
});
