<?php

use App\Vote;
use App\Nest;
use App\Post;
use App\User;
use App\Comment;
use App\Nestling;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/////
//delete
Route::get('my-form','HomeController@myform');
Route::post('my-form','HomeController@myformPost');
/////

Route::get('/', function () {
    return view('home');
});

Route::get('/test', function () {
    return view('partials.test');
});

Route::get('/featured', function () {
    return view('posts.show');
});


Route::get('/test', function () {
    return 'hello';
});

/*
Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', '
PagesController@services');
*/
Auth::routes();

Route::bind('nest', function ($nest) {
    return Nest::where('name', $nest)->firstOrFail() ?? abort(404);
});
Route::bind('post', function ($post) {
    return Post::where('id', $post)->firstOrFail() ?? abort(404);
});
Route::bind('comment', function ($comment) {
    return Comment::where('id', $comment)->firstOrFail() ?? abort(404);
});
Route::bind('nestling', function ($nestling) {
    return Nestling::where('title', $nestling)->firstOrFail() ?? abort(404);
});
Route::bind('username', function ($username) {
    return User::where('username', $username)->firstOrFail() ?? abort(404);
});


Route::post('{posts}/{nest}/store','PostsController@store')->name('posts.store');
Route::post('posts/{nest}/{nestling}/store','PostsController@store')->name('posts.nestling.store');

Route::post('comments/updateComment','CommentsController@updateVote');
Route::post('comments/create','CommentsController@create');



//Route::get('posts/create','PostsController@create')->name('posts.create');
Route::get('posts/{nest}/n/{nestling}/create','PostsController@create')->name('posts.nestling.create');
Route::get('posts/{nest}/create','PostsController@create')->name('posts.create');


Route::get('posts/{nest}/n/{nestling}','PostsController@index')->name('nestlings.index');
//Route::get('posts/{nest}/n','PostsController@index')->name('posts.nestlings.index');

Route::get('posts/{nest}/{post}/{comment}','PostsController@showCommentThread')->name('showCommentThread');

Route::get('posts/{nest}/{post}','PostsController@show')->name('posts.show');
Route::get('posts/{nest}/n/{nestling}/{post}','PostsController@showViaNestling')->name('posts.nestling.show');

//Route::get('posts','PostsController@index')->name('posts.index');
Route::get('posts/{nest}','PostsController@index')->name('posts.index');



Route::post('posts/updateVote','PostsController@updateVote');
Route::delete('posts/{post}', 'PostsController@destroy')->name('posts.destroy');

// Users Controller
Route::get('u/{username}', 'UsersController@show')->name('users.show');



//Route::resource('posts', 'PostsController');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/home', 'HomeController@index')->name('home');
