<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Entity;
use App\Vote;
use Carbon\Carbon;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 25;

        for ($i = 0; $i < $count; $i++) {
            $entity = Entity::create ([
                'entity_type' => 'post',
            ]);
            Post::create ([
                'id' => $entity->id,
                'title' => 'Test post title'.$i,
                'body' => 'Test post body '.$i,
                'user_id' => 1,
                'cover_image' => 'noimage.jpg',
                'created_at' => Carbon::now()->addSeconds($i*2),
            ]);
        }
    }
}
