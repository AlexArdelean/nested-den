<?php

use App\Nestling;
use App\Nest;
use App\Entity;
use Illuminate\Database\Seeder;

class NestlingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nestWhatever = Nest::where('slug', '=', 'basketball')->first();
        $entity = Entity::create ([
            'entity_type' => 'nestling',
        ]);        
        Nestling::create ([
            'id' => $entity->id,
            'nest_id' => $nestWhatever->id,
            'title' => 'The Lakers',
            'description' => 'Best team ever'
        ]);
        $entity = Entity::create ([
            'entity_type' => 'nestling',
        ]);
        Nestling::create ([
            'id' => $entity->id,
            'nest_id' => $nestWhatever->id,
            'title' => 'Bucks',
            'description' => 'A team'
        ]);
        $entity = Entity::create ([
            'entity_type' => 'nestling',
        ]);        
        Nestling::create ([
            'id' => $entity->id,
            'nest_id' => $nestWhatever->id,
            'title' => 'Bulls',
            'description' => 'Decent'
        ]);

    }
}
