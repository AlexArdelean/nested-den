<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Helpers\updateVote;
use App\Post;
use App\Entity;
Use App\Comment;

class CommentsController extends Controller
{
    /**
     * Create comment via ajax
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'login' => auth()->user() ? '' : 'required',
            'post_id' => 'required|exists:posts,id',
            'content' => '',
            'thread_even' => ''
        ]);

        $post_id = $request->input('post_id');
        $content = $request->input('content');
        $parent_id = $request->input('parent_id');
        $thread_even = $request->input('thread_even');
        $nest = Post::where('id', $post_id)->first()->nests()->first()->name;

        //Create comment
        $comment = new Comment;
        $comment->makeComment($parent_id, $post_id, $content);

        $commentThread = '';
        if ($thread_even == 'true')
            $commentThread = '<div class="thread even">';
        else
            $commentThread = '<div class="thread">';

        $replyHtml =  $commentThread
                            .view('partials.createComment', ['comment'=>$comment, 'nest'=>$nest])->render().
                        '</div>';

        return response()->json([
            'replyHtml' => $replyHtml
        ]);
    }

    /**
     * Updates vote value for comment
     *
     * @return \Illuminate\Http\Response
     */
    public function updateVote (Request $request)
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
            'updatedScore'=> $updatedScore,
            'upvotes' => $upvotes,
            'downvotes' => $downvotes
        ]);
    }
}
