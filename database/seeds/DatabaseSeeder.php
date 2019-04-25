<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(VotesTableSeeder::class);
        $this->call(NestsTableSeeder::class);
        $this->call(NestPostTableSeeder::class);
        $this->call(NestlingTableSeeder::class);
    }
}
