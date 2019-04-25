<?php

use Illuminate\Database\Seeder;
use App\Entity;
use App\Nest;

class NestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity = Entity::create ([
            'entity_type' => 'nest',
        ]);
        Nest::create ([
            'id' => $entity->id,
            'name' => 'Whatever',
            'slug' => 'whatever'
        ]);

        $entity = Entity::create ([
            'entity_type' => 'nest',
        ]);
        Nest::create ([
            'id' => $entity->id,
            'name' => 'Basketball',
            'slug' => 'basketball'
        ]);

        $entity = Entity::create ([
            'entity_type' => 'nest',
        ]);
        Nest::create ([
            'id' => $entity->id,
            'name' => 'Football',
            'slug' => 'football'
        ]);

        $entity = Entity::create ([
            'entity_type' => 'nest',
        ]);
        Nest::create ([
            'id' => $entity->id,
            'name' => 'Volleyball',
            'slug' => 'volleyball'
        ]);

        $entity = Entity::create ([
            'entity_type' => 'nest',
        ]);
        Nest::create ([
            'id' => $entity->id,
            'name' => 'Swimming',
            'slug' => 'swimming'
        ]);
    }
}
