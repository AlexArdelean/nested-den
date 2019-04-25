<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'id' => function () {return factory(App\Entity::class,1)->create()->id;},
        'parent_id' => $faker->parent_id,
        'user_id' => $faker->user_id,
        'post_id' => $faker->post_id,
        'body' => $faker->body,
    ];
});

/*    factory(App\User::class, 50)->create()->each(function ($user) {
        $user->posts()->save(factory(App\Post::class)->make());
    });*/
