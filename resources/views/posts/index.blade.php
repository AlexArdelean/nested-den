@extends('layouts.layout')

@section('content')


<h1>{{$nest}}@if(isset($nestling)): {{$nestling->title}}@endif</h1>

@if(isset($nestlings))
  @foreach($nestlings as $n)
    @include('components.nestling', ['nestling' => $n])
  @endforeach
@endif

@if(count($posts) > 0)
  @foreach($posts as $p)
    @include('components.submission', ['post' => $p])
  @endforeach
@else
  <h2>No Posts</h2>
@endif

@endsection

@section('extra-js')
<script type="text/javascript">
  var page = 1;
  let processing = false;
    $(document).scroll(function(){
        if(($(document).height()-$(window).height()-$(document).scrollTop()) < 400 && processing == false){
            processing = true;
            loadMorePosts(++page);
        }
    });

function loadMorePosts(page){
      container = document.getElementById('content-container');
      container.insertAdjacentHTML('beforeend', '<div class="loader"></div>');
      let nestling = '{{isset($nestling) ? $nestling->title : ''}}';
      if (nestling == '')
        var url = "/posts/{{$nest}}?page=" + page;
      else
        var url = "/posts/{{$nest}}/n/" + nestling + "?page=" + page;
      $.ajax({
          url: url,
          complete: function () {
            //processing = false;
          }
          ,
          success: function(data) {
            container.querySelector('.loader').remove();
            $(container).append(data.posts);
            processing = false;

            if (data.posts == '') {
              processing = true;
              container.append('No more posts');
            }
          }
          ,
          error: function(xhr, status, error) {
            processing = false;
            alert(xhr.responseText);
          }
      });
};

</script>
@endsection