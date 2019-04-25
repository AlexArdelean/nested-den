<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Datetime;
use Carbon\Carbon;

class Entity extends Model
{
    // Table Name
    protected $table = 'entities';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    /**
     * Sum of all upvotes for a post
     *
     * @return int
     */
    public function upvotes ()
    {
        $votes = $this->votes()->get();

        $upvotes = 0;
        foreach ($votes as $vote) {
            $value = $vote->value;
            $value > 0 ? $upvotes++ : '';
        }
        return $upvotes;  
    }

    /**
     * Sum of all downvotes for a post
     *
     * @return int
     */
    public function downvotes ()
    {
        $votes = $this->votes()->get();

        $downvotes = 0;
        foreach ($votes as $vote) {
            $value = $vote->value;
            $value < 0 ? $downvotes++ : '';
        }
        return $downvotes;  
    }

    /**
     * Sum of all upvotes and downvotes for a post
     *
     * @return int
     */
    public function sumOfVotes() 
    {
        $votes = $this->votes()->get();

        $sum = 0;
        foreach ($votes as $vote) {
            $value = $vote->value;
            if($value > 0)
                $sum += 1;
            elseif ($value < 0) {
                $sum-=1;
            }
        }
        return $sum;
    }

    /**
     * Finds out how long ago an entity was created 
     * in days, hours or minutes
     *
     * @return string
     */
    public function timeAgo()
    {
        // Find difference between now and created at
        $now = Carbon::now();
        $now->toDateTimeString();
        $created_at = $this->created_at;
        $created_at->toDateTimeString();
        $dateTime = $now->diff($created_at);

        $days = $dateTime->d;
        $hours = $dateTime->h;
        $minutes = $dateTime->i;

        if ($days > 0) {
            $timeAgo = "$days days ago";
        }
        elseif ($hours > 0) {
            $hours = $hours + ($minutes / 60);
            $hours = number_format($hours, 1, '.', '');
            $timeAgo = "$hours hours ago";
        }
        else {
            $timeAgo = "$minutes minutes ago";
        }
        return $timeAgo;
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'entity_id');
    }
}
