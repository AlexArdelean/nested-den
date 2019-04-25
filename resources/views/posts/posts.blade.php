@extends('layouts.layout')

@section('title', 'Posts')

@section('extra-css')

@endsection

@section('content')
  <a href="{{route('posts.show', '')}}" class="btn btn-dark">Go Back</a>
  <h1>{{$post->title}}</h1>
  <img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
  <br><br>
  <div>
    {!!$post->body!!}
  </div>
  <h1>
  <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
  <h1>
  @if(!Auth::guest())
    @if(Auth::user()->id == $post->user_id)
      <a href="/posts/{{$post->id}}/edit" class="btn btn-dark">Edit</a>

      {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'POST', 'class' => 'float-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
      {!!Form::close()!!}
    @endif
  @endif
@endsection

@section('extra-js')
@endsection