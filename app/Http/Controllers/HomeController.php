<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

////////
//delete
use Validator;
use Helpers\updateVote;
use App\Post;
//delete
//////

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
///////////////////////////////////////////////////////////////////
    ////////////////////DELETE THIS ///////////////////////////
    /**
     * Display a listing of the myform.
     *
     * @return \Illuminate\Http\Response
     */
    public function myform()
    {
        return view('myform');
    }
    /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function myformPost(Request $request)
    {
        $post_id = $request->input('post_id');
        $value = $request->input('value');

        updateVote($post_id, $value);

        // Find score after vote
        $post = Post::find($post_id);
        $updatedScore = $post->sumOfVotes();

        return response()->json(['success'=>'Added new records.','updatedScore'=> $updatedScore]);



        /*
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);
        if ($validator->passes()) {
            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
        */
    }
////////////////////////DELETE THIS //////////////////////////
//////////////////////////////////////////////////////////////
}
