@extends('layouts.layout')


@section('content')
  <h1>Edit Post "{{$post->title}}"</h1>

<form method="post" action="{{ route('posts.update', $post->id) }}" formenctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{$post->title}}" required>
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <input type="text" class="form-control" id="body" name="body" value="{{$post->body}}" required>
    </div>
    {{method_field('PUT')}}
    <input type="submit" value="Submit" class="btn btn-primary">
</form>

@endsection