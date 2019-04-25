<?php

use Illuminate\Database\Seeder;
use App\Comment;
use App\Entity;
use App\Post;
use App\User;

/**
 * Creates comment tree at given root comment
 * Each depth has $commentCount amount of comments
 * 1 comment line extends to the bottom depth
 *
 * @param  int  $depth
 * @param  int  $root_id
 * @param  int  $post_id
 * @return void
 */
function createCommentTree($depth, $root_id, $post_id, $currentDepth) {
    $users = User::all()->toArray();
    if ($depth === 0) {
        return 0;
    }
    else {
        $parentRaffle = array();
        $commentCount = rand(1,3);
        for ($i = 1; $i <= $commentCount; $i++) {
            $entity = Entity::create ([
                'entity_type' => 'comment',
            ]);
            Comment::create ([
                'id' => $entity->id,
                'parent_id' => $root_id,
                'user_id' => $users[array_rand($users)]['id'],
                'post_id' => $post_id,
                'body' => 'Reply Level: '.($currentDepth+1),
            ]);
            $parentRaffle[] = $entity->id;
        }
        $index = array_rand($parentRaffle);
        createCommentTree(--$depth, $parentRaffle[$index], $post_id, ++$currentDepth);
    }
}

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        $users = User::all()->toArray();

        foreach ($posts as $post) {
            $depth = rand(0, 4);
            $root_comments = rand(4,6);
            for ($i = 1; $i <= $root_comments; $i++) {
                $entity = Entity::create ([
                    'entity_type' => 'comment',
                ]);
                Comment::create ([
                    'id' => $entity->id,
                    'parent_id' => 0,
                    'user_id' => $users[array_rand($users)]['id'],
                    'post_id' => $post->id,
                    'body' => 'Comment Root',
                ]);
                createCommentTree($depth, $entity->id, $post->id, 0);
                $depth = rand(0, 4);
            }
        }
    }
}
