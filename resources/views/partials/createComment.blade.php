<div class="collapsed">
    <div class="expand-button">{+}</div>
    <ul class="tagline">
        <li><a href="{{route('users.show', $comment->user()->first()->username)}}">{{$comment->user()->first()->username}}</a></li>
        <li class="points">{{$comment->sumOfVotes()}}</li> points
        <li class="upvotes">(+{{$comment->upvotes()}}</li> |
        <li class="downvotes">-{{$comment->downvotes()}})</li>
        <li>{{$comment->timeAgo()}}</li>
    </ul>
</div>
<div class="comment" id="comment-{{$comment->id}}" comment-id="{{$comment->id}}">
    <div class="voting">
        <div class="centered-vertically">
            <div class="upvote" comment-id="{{$comment->id}}"></div>
            <div class="downvote" comment-id="{{$comment->id}}"></div>
        </div>
    </div>
    <div class="body">
        <ul class="tagline">
            <li class="collapse-button">{-}</li>
            <li><a href="{{route('users.show', $comment->user()->first()->username)}}">{{$comment->user()->first()->username}}</a></li>
            <li class="points">{{$comment->sumOfVotes()}}</li> points
            <li class="upvotes">(+{{$comment->upvotes()}}</li> |
            <li class="downvotes">-{{$comment->downvotes()}})</li>
            <li>{{$comment->timeAgo()}}</li>
        </ul>

        <p>{{ $comment->body }}</p>

        <ul class="extra">
            <li><a href="{{route( 'showCommentThread', [$nest, 'post_id' => $comment->post()->first(), $comment->id ] )}}">Link</a></li>  
            @if(auth()->user())
                <li><span class="reply" data-id="{{$comment->id}}">Reply</span></li>
            @endif
        </ul> 
    </div>
</div>
@push('extra-js')
<script type="text/javascript">
(function(){ //Main
    var comment = $('.comment').filter('[comment-id="{{$comment->id}}"]')[0];
    var collapseButton = comment.querySelector('.collapse-button');
    var expandButton = comment.parentNode.querySelector('.expand-button');

    collapseButton.addEventListener('click', collapse);
    expandButton.addEventListener('click', expand);

    function collapse () {
        let thread = this.parentNode.parentNode.parentNode.parentNode;
        $(thread).children().hide();
        $(thread.querySelector('.collapsed')).show();
    }
    function expand () {
        $(this.parentNode.parentNode).children().show();
        $(this.parentNode).hide();
    }
})();
</script>
@endpush