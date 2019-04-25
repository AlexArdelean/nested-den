<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\NestPost;
use App\Nest;

class NestPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        $nests = Nest::all();

        foreach ($posts as $post) {
            NestPost::create ([
                'nest_id' => $nests[array_rand($nests->toArray())]['id'],
                'post_id' => $post->id
            ]);
        }

    }
}
