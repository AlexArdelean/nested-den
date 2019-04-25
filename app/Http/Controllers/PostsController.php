<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use App\Entity;
use App\Nest;
use App\NestPost;
use App\Nestling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\updateVote;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth', ['except' => ['index', 'show', 
          'updateVote', 'showCommentThread']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $nest, $nestling = null)
    {
        if(isset($nestling))
            if(!$nest->containsNestling($nestling))
                return abort(404);

        $pagination = 5;

/*        if(request()->discussionNavbar)
          dd('show Discussion');
        else
          dd('dont show');
*/

        if ($nest->slug == 'whatever') {
            $posts = Post::orderBy('created_at', 'desc')->paginate($pagination);
        }
        else {
            if (isset($nestling)){
                $posts = Post::whereHas('nests', function($query){
                    $query->where('nest_id', request()->nest->id);
                })
                ->whereHas('nestlings', function($query){
                    $query->where('nestling_id', request()->nestling->id);
                })
                ->orderBy('created_at', 'desc')->paginate($pagination);
            }
            else {
                $posts = Post::with('nests')->whereHas('nests', function($query){
                    $query->where('name', request()->nest->name);
                })->orderBy('created_at', 'desc')->paginate($pagination);
            }
        }

        if (request()->sort == 'top') {
            //
        }

        foreach ($posts as $post) {
          $post->score = $post->sumOfVotes();
          $post->upvotes = $post->upvotes();
          $post->downvotes = $post->downvotes();
        }

        if($request->ajax()){
            $ajaxPosts = '';
            foreach ($posts as $post) {
                $ajaxPosts = $ajaxPosts.view('components.submission', ['post'=>$post, 'nest' => $nest->name])->render();
            }

            return response()->json([
                'posts' => $ajaxPosts
            ]);
        }

        //dd($nestling);
        return view('posts.index')->with([
          'posts' => $posts,
          'nestlings' => $nest->nestlings() != null && !isset($nestling) ? $nest->nestlings()->get() : null,
          'nest' => $nest->name,
          'nestling' => $nestling
        ]);
    }

    /**
     * Shows post via route posts/{nest}/{post}
     * Needs to call showViaNestling so I can keep
     * the order correct in the URL as the middle
     * of the URL is null and php only allows default
     * functions in order
     *
     * @param  \Nest  $nest
     * @param  \Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($nest, $post)
    {
      return $this->showViaNestling($nest, null, $post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Nest  $nest
     * @param  \Post  $post
     * @param  \Nestling  $nestling
     * @return \Illuminate\Http\Response
     */
    public function showViaNestling($nest, $nestling, $post)
    {
      function createTree(&$list, $parent){
          $tree = array();
          foreach ($parent as $k=>$l){
              if(isset($list[$l['id']])){
                  $l['children'] = createTree($list, $list[$l['id']]);
              }
              $tree[] = $l;
          } 
          return $tree;
      }

      $comments = $post->comments()->get();
      if (count($comments)){
        $new = array();
        foreach ($comments as $comment){
            $new[$comment->parent_id][] = $comment;
        }
        $post->comments = createTree($new, $new[0]); // changed
      }

      //$post->comments = $tree;
      $post->score = $post->sumOfVotes();
      $post->upvotes = $post->upvotes();
      $post->downvotes = $post->downvotes();
      $post->showContent = true;

      return view('posts.show')->with([
        'post' => $post,
        'nest' => $nest->name
      ]);
    }

    /**
     * Display post and specific comment in post
     * and all children.
     * @param  \Nest  $nest
     * @param  \Post  $post
     * @param  \Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function showCommentThread($nest, $post, $comment)
    {
      function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l['id']])){
                $l['children'] = createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        } 
        return $tree;
      }

      $comments = $post->comments()->get();
      $comment = array($comment);
      if (count($comments)){
        $new = array();
        foreach ($comments as $com){
            $new[$com->parent_id][] = $com;
        }
        $post->comments = createTree($new, $comment); // changed
      }

      //$post->comments = $tree;
      $post->score = $post->sumOfVotes();
      $post->upvotes = $post->upvotes();
      $post->downvotes = $post->downvotes();
      $post->showContent = true;

      return view('posts.show')->with([
        'post' => $post, 
        'commentThread' => true, 
        'nest' => $nest->name
      ]);
    }

    /**
     * Show the form for creating a post, if no nest specified it
     * posts to the nest whatever
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create($nest = ['name' => 'whatever'], $nestling = null)
    {
      //dd($nestling['title']);
        return view('posts.create')->with([
          'nest' => $nest['name'],
          'nestling' => $nestling
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Nest  $nest
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $nest, $nestling = null)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

      //Handle File Upload
      if($request->hasFile('cover_image')){
        // Get filename with the extension
        $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just extension
        $extension = $request->file('cover_image')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // Upload Image
        $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
      } else {
        $fileNameToStore = 'noimage.jpg';
      }

      //Create new Entity
      $entity = new Entity;
      $entity->entity_type  = 'post';
      $entity->save();

      //Create Post
      $post = new Post;
      $post->id = $entity->id;
      $post->title = request()->title;
      $post->body = request()->body;
      $post->user_id = auth()->user()->id;
      $post->cover_image = $fileNameToStore;
      $post->save();

      // Connect post to Nest via NestPost
      $nestPost = new NestPost;
      $nestPost->post_id = $entity->id;
      $nestPost->nest_id = $nest->id;

      if(isset($nestling))
        $nestPost->nestling_id = $nestling->id;

      $nestPost->save();

      if (isset($nestling))
        $nestling = $nestling->title;
      else
        $nestling = '';

      return redirect('/posts/'.$nest->name.'/n/'.$nestling)->with('success', 'Post Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $post = Post::find($id);

      //Check for correct user
      if(auth()->user()->id != $post->user_id){
        return redirect('/posts')->with('error', 'Unauthorized Page');
      }

      return view('posts.edit')->with(['post' => $post]);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
      ]);

      //Handle File Upload
      if($request->hasFile('cover_image')){
        // Get filename with the extension
        $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just extension
        $extension = $request->file('cover_image')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // Upload Image
        $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
      }

      //Update Post
      $post = Post::find($id);
      $post->title = $request->input('title');
      $post->body = $request->input('body');
      if($request->hasFile('cover_image'))
        $post->cover_image = $fileNameToStore;
      $post->save();

      return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($post)
    {
      //Check for correct user
      if(auth()->user()->id != $post->user_id){
        return redirect('/posts')->with('error', 'Unauthorized Page');
      }

      if($post->cover_image != 'noimage.jpg'){
        //Delete Image
        Storage::delete('public/cover_images/'.$post->cover_image);
      }

      $post->delete();
      return redirect('/posts')->with('success', 'Post Removed');
    }

    /**
     * Updates vote value for post
     *
     * @return \Illuminate\Http\Response
     */
    public function updateVote (Request $request)
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
            'updatedScore'=> $updatedScore,
            'upvotes' => $upvotes,
            'downvotes' => $downvotes
        ]);
    }

}