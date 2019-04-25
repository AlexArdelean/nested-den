@extends('layouts.layout')

@section('content')
  <h1>Create Posts</h1>
  
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(!isset($nestling))
    <form method="post" action="{{ route('posts.store', $nest) }}" enctype="multipart/form-data">
@else
    <form method="post" action="{{ route('posts.nestling.store', ['nest' => $nest, 'nestling' => $nestling->title]) }}" enctype="multipart/form-data">
@endif
    @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Title" autocomplete="off" required autofocus>
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <input type="text" class="form-control" id="body" name="body" placeholder="Body" autocomplete="off" required>
    </div>

    <div class="form-group">
        <label for="cover_image">Choose file to upload</label>
        <input type="file" name="cover_image" id="cover_image" placeholder="">
    </div>

    <input type="submit" value="Submit" class="btn btn-primary">
</form>

@endsection