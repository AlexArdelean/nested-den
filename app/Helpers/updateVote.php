<?php

use App\Vote;

if (!function_exists('updateVote')) {

     /**
     * Updates votes table in database
     *
     * @param int $entity_id
     * @param int $user_id
     * @param int $value
     * @return 
     */   
    function updateVote($entity_id, $value) {
        $user_id = auth()->user()->id;
        
        try {
            // Retrieve particular Vote from database
            $vote = Vote::where('entity_id', '=', $entity_id)
                            ->where('user_id', '=', $user_id)
                            ->first();
            // If doesn't exist make new vote otherwise update value
            if (is_null($vote))
            {
                $vote = new Vote;
                $vote->entity_id = $entity_id;
                $vote->user_id = $user_id;
                $vote->value = $value;
            }
            else {
                // If they decided to cancel vote cancel it
                if($vote->value == $value)
                    $vote->value = 0;
                // Else add vote like normal
                else {
                    $vote->value = $value;
                }
            }
            $vote->save();

        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}