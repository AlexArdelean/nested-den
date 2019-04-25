<?php

use Illuminate\Database\Seeder;
use App\Vote;
use App\Post;
use App\Comment;
use App\User;

class VotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $posts = Post::all();
        $comments = Comment::all();

        $postsRaffle = array(-1,0,0,0,1,1,1);
        $commentsRaffle = array(-1,0,0,0,0,0,0,0,0,1,1,1);


        foreach ($posts as $post) {
            foreach ($users as $user) {
                Vote::create ([
                    'user_id' => $user->id,
                    'entity_id' => $post->id,
                    'value' => $postsRaffle[array_rand($postsRaffle)],
                ]);                
            }

            /*
            foreach ($users as $user) {
                foreach ($comments as $comment) {
                    Vote::create ([
                        'user_id' => $user->id,
                        'entity_id' => $comment->id,
                        'value' => $commentsRaffle[array_rand($commentsRaffle)],
                    ]);                
                }
            }
            */
        }
    } // End run()
}
