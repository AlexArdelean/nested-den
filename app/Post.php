<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entity;

class Post extends Model
{
    // Table Name
    protected $table = 'posts';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    public function votes(){
        return $this->hasMany('App\Vote', 'entity_id');
    }

    /**
     * Total amount of comments for post
     *
     * @return int
     */
    public function countComments (){ 
        return count($this->comments()->get());
    }

    /**
     * Sum of all upvotes for a post
     *
     * @return int
     */
    public function upvotes (){ 
        $entity = $this->entity()->first();
        return $entity->upvotes();
    }

    /**
     * Sum of all downvotes for a post
     *
     * @return int
     */
    public function downvotes (){
        $entity = $this->entity()->first();
        return $entity->downvotes();
    }

    /**
     * Sum of all upvotes and downvotes for a post
     *
     * @return int
     */
    public function sumOfVotes() {
        $entity = $this->entity()->first();
        return $entity->sumOfVotes();
    }


    /**
     * Finds out how long ago a post was posted 
     * in days, hours or minutes
     *
     * @return string
     */
    public function timeAgo()
    {
        $entity = $this->entity()->first();
        return $entity->timeAgo();
    }


    public function nests()
    {
        return $this->belongsToMany('App\Nest');
    }

    public function nestlings()
    {
        return $this->belongsToMany('App\Nest');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function entity()
    {
        return $this->belongsTo('App\Entity', 'id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
