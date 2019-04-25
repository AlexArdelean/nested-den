@extends('layouts.layout')

@section('content')
  @if(!isset($nestling))
    <a href="{{route('posts.index', [$nest] )}}" class="btn btn-dark">Go Back</a>
  @else
    <a href="{{route('posts.nestling.index', [$nest, $nestling->title] )}}" class="btn btn-dark">Go Back</a>
  @endif
  @if(!Auth::guest())
    @if(Auth::user()->id == $post->user_id)
      <a href="/posts/{{$post->id}}/edit" class="btn btn-dark">Edit</a>
      
      <form method="post" action="{{ route('posts.destroy', [$post->id]) }}" class="float-right">
        @csrf
        {{method_field('DELETE')}}
        <input type="submit" class="btn btn-danger" value="Delete"/>
      </form>
    @endif
  @endif

  @include('components.submission')

  <br>

  @if(!isset($commentThread))
    <form class="replyForm">
      <input type="text" class="content form-group form-control" name="content" autocomplete="off" required autofocus>
      <input type="submit" class="submit btn btn-primary" id="reply-0" data-id="0" value="Submit" >
    </form>
  @else
    <h2>You're viewing a comment thread</h2>
  @endif

  @if (count($post->comments))
      @foreach ($post->comments as $comment)
          @include('partials.commentTree', array('post'=>$post,'comments'=>$comment,'even'=>false))
      @endforeach
  @else
      <h1 id="no-comments">No Comments</h1>
  @endif

  <br>


@endsection

@section('extra-js')
<script type="text/javascript">

var UPVOTE = 1;
var DOWNVOTE = -1;
var ORIGINAL_POST = 0; 

// Upvote button on each comment
$('body').on('click', '.comment .upvote', {vote: UPVOTE}, submitCommentVote);

// .downvote button on each comment
$('body').on('click', '.comment .downvote', {vote: DOWNVOTE}, submitCommentVote);

// Create reply via ajax when submiting reply form
$('body').on('click', '.replyForm .submit', submitReply);

// .cancel button on each replyForm
$('body').on('click', '.replyForm .cancel', removeReplyForm);

// Show text form when clicking replyForm button
$('body').on('click', '.comment .reply', showReplyForm);



function removeReplyForm(){
  this.parentNode.remove();
}

function submitCommentVote(event) {
  $('body').off('click', '.comment .upvote');
  $('body').off('click', '.comment .downvote');

  let comment_id = this.getAttribute('comment-id');
  updateCommentVotes(comment_id, event.data.vote);
}

function showReplyForm() {
  let comment_id = this.getAttribute('data-id');
  let comment = document.getElementById('comment-'+comment_id);
  let commentBody = comment.querySelector('.body');

  // If already exists don't create a new one
  if (comment.querySelector('.replyForm'))
    return;

  let replyFormElement = createReplyForm(comment_id);

  commentBody.appendChild(replyFormElement);
  replyFormElement.querySelector('.content').focus();
};

function submitReply (event) {
  let replyFormElement = this.parentNode;
  let contentElement = replyFormElement.querySelector('.content');
  let content = contentElement.value;
  let post_id = {{$post->id}};
  let parent_id = this.getAttribute('data-id');

  if (content == '')
    return;

  $('body').off('click', '.replyForm .submit');

  // Remove reply form since comment is being created
  if(parent_id != ORIGINAL_POST)
    replyFormElement.remove();

  // Can add loading icon here in future

  // Creates comment and attached it to html
  submitReplyAjax(content, post_id, parent_id);

  // Clear content on form
  contentElement.value = null;
};

function threadType(comment_id) {
  let comment = document.getElementById('comment-'+comment_id);
  let thread = comment.parentNode;
  return thread.classList.contains('even');
}

function submitReplyAjax (content, post_id, parent_id){
  let thread_even = false;
  if (parent_id != ORIGINAL_POST)
    thread_even = !threadType(parent_id);
  $.ajax({
      url: "/comments/create",
      type:'POST',
      data: {content:content, post_id:post_id, parent_id:parent_id, thread_even:thread_even},
      complete: function () {
        $('body').on('click', '.replyForm .submit', submitReply);
      }
      ,
      success: function(data) {
        addReplyHtml(data.replyHtml, parent_id);
      }
      ,
      error: function(xhr, status, error){
        alert(xhr.responseText);
      }
  });
}

// when replying to direct post it needs to put 
function addReplyHtml(replyHtml, parent_id) {
  if (parent_id != ORIGINAL_POST) {
    let parentComment = document.getElementById('comment-'+parent_id);
    $(parentComment).after(replyHtml);
  }
  else {
    var contentContainer = document.getElementById('content-container');
    originalReplyForm = contentContainer.querySelector('.replyForm');
    $(originalReplyForm).after(replyHtml);

    let firstComment = document.getElementById('no-comments');
    if (firstComment)
      firstComment.remove();
  }
}

function updateCommentVotes (comment_id, value) {
  $.ajax({
      url: "/comments/updateComment",
      type:'POST',
      data: {comment_id:comment_id, value:value},
      complete: function () {
          $('body').on('click', '.comment .upvote', {vote: UPVOTE}, submitCommentVote);
          $('body').on('click', '.comment .downvote', {vote: DOWNVOTE}, submitCommentVote);
      }
      ,
      success: function(data) {
        let comment = document.getElementById("comment-"+comment_id);
        refreshVotesHtml(comment, data.updatedScore, data.upvotes, data.downvotes);
      }
      ,
      error: function(err){
        displayErrors(err);
      }
  }); // End Ajax Call
} // End function updateComment

function refreshVotesHtml (comment, score, upvotes, downvotes) {
  comment.querySelector('.points').innerHTML = score;
  comment.querySelector('.upvotes').innerHTML = '(+'+upvotes;
  comment.querySelector('.downvotes').innerHTML = '-'+downvotes+')';
}

function createReplyForm (comment_id) {
  var replyForm = document.createElement('form');
  replyForm.className = 'replyForm';

  var content = document.createElement('input');
  content.type = 'text';
  content.className = 'content form-control form-group';
  content.name = 'content';
  content.setAttribute('autocomplete','off');
  content.setAttribute('required', 'true');

  var submitButton = document.createElement('input');
  submitButton.type = 'submit'; //or button
  submitButton.value = 'Submit';
  submitButton.className = 'submit btn btn-primary';
  submitButton.setAttribute('data-id', comment_id);

  var cancelButton = document.createElement('input');
  cancelButton.type = 'button';
  cancelButton.value = 'Cancel';
  cancelButton.className = 'cancel btn btn-primary';
  cancelButton.setAttribute('data-id', comment_id);

  replyForm.appendChild(content);
  replyForm.appendChild(submitButton);
  replyForm.appendChild(cancelButton);

  return replyForm;
}
function displayErrors (err) {
  let errors = err.responseJSON.errors;
  for (let error in errors){
    alert(err.status+': '+errors[error]);
  }
}
</script>

@endsection