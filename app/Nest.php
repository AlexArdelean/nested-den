<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nest extends Model
{
    // Table Name
    protected $table = 'nests';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function containsNestling ($nestling) {
        $nestlings = $this->nestlings()->get();
        foreach ($nestlings as $n) {
            if($n == $nestling)
                return true;
        }
        return false;
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function nestlings()
    {

        return $this->hasMany('App\Nestling')->orderBy('priority', 'asc');
    }

    public function entity()
    {
        return $this->belongsTo('App\Entity', 'id');
    }
}
