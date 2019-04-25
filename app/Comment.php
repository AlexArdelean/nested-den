<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entity;

class Comment extends Model
{
    // Table Name
    protected $table = 'comments';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;


    /**
     * Create a new comment
     *
     * @return int
     */
    public function makeComment($parent_id, $post_id, $content) {
        //Create new Entity for comment
        $entity = new Entity;
        $entity->entity_type  = 'comment';
        $entity->save();

        $this->id = $entity->id;
        $this->parent_id = $parent_id;
        $this->user_id = auth()->user()->id;
        $this->post_id = $post_id;
        $this->body = $content;
        $this->save();
    }

    /**
     * Sum of all upvotes for a comment
     *
     * @return int
     */
    public function upvotes (){ 
        $entity = $this->entity()->first();
        return $entity->upvotes();
    }

    /**
     * Sum of all downvotes for a comment
     *
     * @return int
     */
    public function downvotes (){
        $entity = $this->entity()->first();
        return $entity->downvotes();
    }
    /**
     * Sum of all upvotes and downvotes for a comment
     *
     * @return int
     */
    public function sumOfVotes() {
        $entity = $this->entity()->first();
        return $entity->sumOfVotes();
    }

    /**
     * Finds out how long ago a comment was posted 
     * in days, hours or minutes
     *
     * @return string
     */
    public function timeAgo(){
        $entity = $this->entity()->first();
        return $entity->timeAgo();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function post(){
        return $this->belongsTo('App\Post');
    }
    public function entity(){
        return $this->belongsTo('App\Entity', 'id');
    }
}