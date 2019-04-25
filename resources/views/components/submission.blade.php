<div class="submission" id="submission-{{$post->id}}" data-id="{{$post->id}}">
  <div class="voting">
      <div class="centered-vertically">
        <div class="upvote"></div>
        <div class="score">{{$post->score}}</div>
        <div class="downvote"></div>
      </div>
  </div>

  @if($post->cover_image != 'noimage.jpg')
    <a href="{{route('posts.show', [$nest, $post->id])}}" class="thumbnail">
      <img src="/storage/cover_images/{{$post->cover_image}}">
    </a>
  @endif

  <div class="entry">
    <div class="title-wrapper">
      @if(!isset($nestling))
        <p class="title"><a href="{{route('posts.show', [$nest, $post->id])}}">{{$post->title}}</a></p>
      @else
        <p class="title"><a href="{{route('posts.nestling.show', [$nest, $nestling->title, $post->id])}}">{{$post->title}}</a></p>
      @endif
    </div>
    <div class="tagline-wrapper">
      <div class="tagline">
        {{$post->timeAgo()}} by <a href="{{ route('users.show', $post->user->username) }}">{{$post->user->username}}</a> to <a href="{{route('posts.index', $post->nests()->first()->name)}}">{{$post->nests()->first()->name}}</a> (+<span class="upvotes">{{$post->upvotes}}</span>|-<span class="downvotes">{{$post->downvotes}}</span>)
      </div>
    </div>
    <div class="extra-wrapper">
      <ul class="extra">
        <li>
          <a href="{{route('posts.show', [$nest, $post->id] )}}">{{$post->countComments()}} comments</a>
        </li>
        <li>
          save
        </li>
        <li>
          report
        </li>
      </ul>
    </div>
  </div> <!-- End Entry -->

</div>

<script type="text/javascript">
  var PROCESSING = false;
  var UPVOTE_VALUE = 1;
  var DOWNVOTE_VALUE = -1;

  (function(){ //Main
    // Create ajax buttons on submissions
    var submission = $('.submission').filter('[data-id="{{$post->id}}"]')[0];

    var post_id = submission.getAttribute('data-id');

    //Alternate between showing and hiding content
    if (@json($post->showContent)){
      showSubmissionContent(submission);
      submission.addEventListener('click', removeSubmissionContent);
    }
    else
      submission.addEventListener('click', submissionClick);

    var upvoteButton = submission.querySelector('.upvote');
    upvoteButton.addEventListener('click', function() {
      if(!PROCESSING)
        updatePostVote(post_id, UPVOTE_VALUE);
    });

    var downvoteButton = submission.querySelector('.downvote');
    downvoteButton.addEventListener('click', function() {
      if(!PROCESSING)
        updatePostVote(post_id, DOWNVOTE_VALUE);
    });

    function submissionClick(e){
      let click = $(e.target);
      if( !clickOn(click, ['.title', '.tagline', '.extra', '.body', '.voting']) ){
        showSubmissionContent(this);
        this.removeEventListener('click', submissionClick);
        this.addEventListener('click', removeSubmissionContent);
      }
    };

    function removeSubmissionContent(e){
      let click = $(e.target);
      if ( !clickOn(click, ['.title', '.tagline', '.extra', '.body', '.voting']) ){
        this.querySelector('.content-wrapper').remove();
        this.removeEventListener('click',removeSubmissionContent);
        this.addEventListener('click', submissionClick);
      }
    }

    function showSubmissionContent(submission){
      let entry = submission.querySelector('.entry');
      let submissionContent = @json($post->body);
      let contentElement = createContentElement(submissionContent);
      entry.appendChild(contentElement);
    }

    function clickOn (click, children) {
      for (let i = 0; i < children.length; ++i) {
          if(click.closest(children[i]).length)
            return true;
      }
      return false;
    }

    function createContentElement(postContent) {
      let content = document.createElement('div');
      content.className = 'content-wrapper';

      let body = document.createElement('p');
      body.className = 'body';
      body.innerHTML = postContent;

      content.appendChild(body);

      return content;
    };

    function updatePostVote (post_id, value) {
      PROCESSING = true;
      $.ajax({
          url: "/posts/updateVote",
          type:'POST',
          data: {post_id:post_id, value:value},
          complete: function () {
            PROCESSING = false;
          }
          ,
          success: function(data) {
            var post = $(".submission[data-id='"+post_id+"']");
            post.find('.score').html(data.updatedScore);
            post.find('.upvotes').html(data.upvotes);
            post.find('.downvotes').html(data.downvotes);
          }
          ,
          error: function(err){
            showLoginPrompt();
            console.log(err.status);
            var errors = err.responseJSON.errors;
            for (var error in errors){
              console.log(err.status+': '+errors[error]);
            }
          }
      });
    };

    function showLoginPrompt(){
      // Get the modal
      var modal = document.getElementById('loginModal');

      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];

      // Open the modal 
      modal.style.display = "flex";

      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
        modal.style.display = "none";
      }

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
    }

  })();
</script>