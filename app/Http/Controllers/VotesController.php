<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Helpers\updateVote;
use App\Post;
Use App\Comment;

class VotesController extends Controller
{
    /**
     * Updates vote value for comment
     *
     * @return \Illuminate\Http\Response
     */
    public function updateComment (Request $request)
    {
        $this->validate($request, [
            'login' => auth()->user() ? '' : 'required',
            'comment_id' => 'required|exists:comments,id',
            'value' => 'required|lte:1|gte:-1'
        ]);

        $comment_id = $request->input('comment_id');
        $value = $request->input('value');

        updateVote($comment_id, $value);

        // Find score after voting
        $comment = Comment::find($comment_id);
        $updatedScore = $comment->sumOfVotes();
        $upvotes = $comment->upvotes();
        $downvotes = $comment->downvotes();

        return response()->json([
            'success'=>'Added new records.',
            'updatedScore'=> $updatedScore,
            'upvotes' => $upvotes,
            'downvotes' => $downvotes
        ]);
    }


    /**
     * Updates vote value for post
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePost (Request $request)
    {
        $this->validate($request, [
            'login' => auth()->user() ? '' : 'required',
            'post_id' => 'required|exists:posts,id',
            'value' => 'required|lte:1|gte:-1'
        ]);

        $post_id = $request->input('post_id');
        $value = $request->input('value');

        updateVote($post_id, $value);

        // Find score after voting
        $post = Post::find($post_id);
        $updatedScore = $post->sumOfVotes();
        $upvotes = $post->upvotes();
        $downvotes = $post->downvotes();

        return response()->json([
            'success'=>'Added new records.',
            'updatedScore'=> $updatedScore,
            'upvotes' => $upvotes,
            'downvotes' => $downvotes
        ]);
    }
}
