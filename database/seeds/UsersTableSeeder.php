<?php

use Illuminate\Database\Seeder;
use App\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersToCreate = 50;

        $faker = Faker\Factory::create();

        User::create ([
            'name' => 'test',
            'username' => 'DudeImBetter',
            'email' => 'test@gmail.com',
            'password' => '$2y$10$ukF2FkMvgex0pAw3XPiUpOtKfMkAY33/5jqz6bD6q.fzkvfwd79Nm',
        ]);

        for ($i=0; $i < $usersToCreate; $i++) {
            $name = $faker->name;
            User::create ([
                'name' => $name,
                'username' => $name,
                'email' => $faker->unique()->safeEmail,
                'password' => '$2y$10$ukF2FkMvgex0pAw3XPiUpOtKfMkAY33/5jqz6bD6q.fzkvfwd79Nm',
            ]);
        }
    }
}
