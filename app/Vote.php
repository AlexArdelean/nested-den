<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Vote extends Model
{
    // Table Name
    protected $table = 'votes';
    // Primary Key
    public $primaryKey = 'entity_id';
    // Timestamps
    public $timestamps = true;



    public function users(){
        return $this->belongsTo('App\User');
    }
    public function entities(){
        return $this->belongsTo('App\Entity');
    }

    // Create composite key with entity_id and user_id
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('entity_id', '=', $this->getAttribute('entity_id'))
            ->where('user_id', '=', $this->getAttribute('user_id'));
        return $query;
    }
}
