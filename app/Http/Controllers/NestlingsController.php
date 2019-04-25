<?php

namespace App\Http\Controllers;

use App\Post;
use App\Nest;
use DB;

use Illuminate\Http\Request;

class NestlingsController extends Controller
{
    private $nests; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 
          'updateVote', 'showCommentThread']]);
        $this->nests = Nest::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($nest, $nestling)
    {
        if(!$nest->containsNestling($nestling))
            return abort(404);

        $pagination = 10;

        if ($nest->slug == 'whatever') {
            $posts = Post::orderBy('created_at', 'desc')->paginate($pagination);
        }
        else {

            $someVariable = 'hello';

            $temp = DB::table('nest_post')
                ->select('*')
                ->where('nest_id', '=', '106');


            $result = DB::table('posts')
                ->join('votes', 'posts.id', '=', 'votes.entity_id');
                //->join($temp, )


/*            $results = DB::select( DB::raw("select
                                                posts.id,
                                                sum(votes.value)
                                            from posts
                                            inner join votes
                                            on posts.id = votes.entity_id
                                            group by posts.id
                                            order by sum;"));*/
            //dd($results);
/*            $posts = Post::whereHas('votes', function($query){
                $query->selectRaw('value, sum(value) as sum')
                ->groupBy('value')
                ;
            })
            ->get();
            dd($posts);*/

            $posts = Post::whereHas('nests', function($query){
                $query->where('nest_id', request()->nest->id);
            })
            ->whereHas('nestlings', function($query){
                $query->where('nestling_id', request()->nestling->id);
            })
            ->orderBy('created_at', 'desc')->paginate($pagination);
        }

        if (request()->sort == 'top') {
            //
        }

        foreach ($posts as $post) {
          $post->score = $post->sumOfVotes();
          $post->upvotes = $post->upvotes();
          $post->downvotes = $post->downvotes();
        }

        //dd($nestling);
        return view('posts.index')->with([
          'posts' => $posts,
          'nests' => $this->nests, 
          'nest' => $nest->name,
          'nestling' => $nestling
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
