<div
@if($even==true) class="thread even"
@else class="thread"
@endif
>
    @include('partials.createComment', ['comment'=>$comment])

    @if (!is_null($comment->children))

        @foreach($comment->children as $comment)
            @include('partials.commentTree', array('post'=>$post,'comments'=>$comment,'even'=>!$even))
        @endforeach
        
    @endif

</div>  