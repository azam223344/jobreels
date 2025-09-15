<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
// use App\Models\Replies;
use App\Models\Post;
use App\Models\DeviceToken;
use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Models\Notifications;
use App\Http\Resources\CommentResource;

class CommentsController extends Controller
{
	public function store(Request $request)
	{
		$request->validate([
			'comment' => 'required|string',
			'post_id' => 'required|exists:posts,id'
		]);

		$comment = Comment::create([
			'user_id' => $request->user()->id,
			'post_id' => $request->post_id,
			'comment' => $request->comment
		]);
        // FireBAse Notification
		$post = Post::where('id', $request->post_id)->first();
		$body = $request->user()->name . " commented on your post (" . $post->name . ")";
		$device_tokens = DeviceToken::where('user_id', $post->user_id)->pluck('value')->toArray();
		$additional_info = [
			"type" => "Comment",
			"id"  => $request->post_id,
		];
		if (count($device_tokens) != 0) {

			$result =  sendPushNotification($body ,$device_tokens ,$additional_info);
		}
        # Add Notification to DB
		Notifications::create([
			'title'=>"Happy Tails TV",
			'notification'=>"{$request->user()->name} commented on your post ({$post->name})",
			'user_id'=>$post->user_id,
			'type'=>'Comment',
			'post_id' => $request->post_id,
		]);

		return [
			'message' => 'success',
			'messageToken' => $request->messageToken,
			'data' => new CommentResource($comment),
			'total_comments' => count($post->comments),
		];
	}

	public function reply(Request $request)
	{

        // return 'HI';
		$request->validate([
			'comment_id' => 'required',
			'post_id' => 'required|exists:posts,id',
			'comment' => 'required',
		]);

		$comment = Comment::create([
			'user_id' => $request->user()->id,
			'comment_id' => $request->post('comment_id'),
			'comment' => $request->comment,
			'post_id' => $request->post_id,
			'is_reply' => 1
		]);
        // FireBAse Notification
		$postUser = Comment::where('id', $request->comment_id)->first();
		$comment_user_id = $postUser->user_id;
		$postUser = Post::where('id', $postUser->post_id)->first();
		$email = User::where('id', $postUser->user_id)->first();
		$body = $request->user()->name . " replied to your comment";
		$device_tokens = DeviceToken::where('user_id', $comment_user_id)->pluck('value')->toArray();  
		$additional_info = [
			"type" => "CommentReply",
			"id"  => $request->post_id,
		];
		if (count($device_tokens) != 0) {
			$result =  sendPushNotification($body ,$device_tokens ,$additional_info);
            // sendPushNotification($body, $device_tokens, $additional_info);
            //try {
              //  Larafirebase::withTitle("Happy Tails TV")
               // ->withBody("{$request->user()->name} Replied to your comment")
             //   ->sendMessage($device_tokens);
           // } catch (\Throwable $th) {
                # Error Sending Notification
         //   }
		}

        # Send Email Notification
     //   SendEmailJob::dispatchAfterResponse(new SendEmailJob([
         //   'to' => $email->email,
       //     'title' => 'Alert | Happy tails TV',
         //   'body' => "{$request->user()->name} Replied to your comment;",
          //  'subject' => 'Alert | Happy tails TV'
       // ]));

         # Add Notification to DB
		Notifications::create([
			'title'=>"Happy Tails TV",
			'notification'=>"{$request->user()->name} replied to your comment",
			'user_id'=>$comment_user_id,
			'type'=>'CommentReply',
			'post_id' => $request->post_id,

		]);

		return [
			'message' => 'success',
			'data' => $comment,
		];
	}

	public function like(Request $request)
	{

		$request->validate([
            // 'comment' => 'required',
			'comment_id' => 'required|exists:comments,id'
		]);

        # Skip if Like already exists
		$is_liked = DB::table('comment_likes')
		->where('comment_id', $request->comment_id)
		->where('user_id', $request->user()->id)->count();
		if ($is_liked) {
			return [
				'message' => 'success',
				'data' => []
			];
		}

		$postUser = Comment::where('id', $request->comment_id)->first();
		$comment_user_id = $postUser->user_id;
		$postUser = Post::where('id', $postUser->post_id)->first();
		$email = User::where('id', $postUser->user_id)->first();
        # Send Email Notification
      //  SendEmailJob::dispatchAfterResponse(new SendEmailJob([
        //    'to' => $email->email,
         //   'title' => 'Alert | Happy tails TV',
          //  'body' => "{$request->user()->name} Liked Your Comment;",
           // 'subject' => 'Alert | Happy tails TV'
       // ]));

		$like = Likes::create([
			'user_id' => $request->user()->id,
			'comment_id' => $request->comment_id,
		]);
		$body = $request->user()->name . " liked your comment";
		$device_tokens = DeviceToken::where('user_id', $comment_user_id)->pluck('value')->toArray();
		$additional_info = [
			"type" => "CommentLike",
			"id"  => $postUser->id,
		];
		if (count($device_tokens) != 0) {
			sendPushNotification($body, $device_tokens, $additional_info);
            //try {
              //  Larafirebase::withTitle("Happy Tails TV")
               // ->withBody("{$request->user()->name} Liked to your comment")
               // ->sendMessage($device_tokens);
           // } catch (\Throwable $th) {
                # Error Sending Notification
            //}
		}

         # Add Notification to DB
		Notifications::create([
			'title'=>"Happy Tails TV",
			'notification'=>"{$request->user()->name} liked your comment",
			'user_id'=>$comment_user_id,
			'type'=>'CommentLike',
			'post_id' => $postUser->id,

		]);

		return [
			'message' => 'success',
			'data' => $like
		];
	}

	function unlike(Request $request)
	{

		$request->validate([
            // 'comment' => 'required',
			'comment_id' => 'required|exists:comments,id'
		]);

		DB::table('comment_likes')
		->where('comment_id', $request->comment_id)
		->where('user_id', $request->user()->id)->delete();

		return [
			'message' => 'success'
		];
	}

	public function likeUnlike(Request $request, $comment)
	{
		$comment = Comment::find($comment);
		if (!$comment)
		{
			return [
				'message' => 'error',
				'error' => 'Comment does not exist.'
			];
		}

		$user_liked = false;

		$oldRecord = Likes::where('comment_id', $comment->id)->where('user_id', $request->user()->id)->first();

		if (!$oldRecord) 
		{
			Likes::forceCreate([
				'user_id' => $request->user()->id,
				'comment_id' => $comment->id,
			]);

			$user_liked = true;
		} 
		else
		{
			$oldRecord->delete();
			
			$user_liked = false;
		}

		if ($user_liked && $request->user()->id != $comment->user_id)
		{
			$body = $request->user()->name . " liked your comment";
			$device_tokens = DeviceToken::where('user_id', $comment->user_id)->pluck('value')->toArray();
			$additional_info = [
				"type" => "CommentLike",
				"id"  => $comment->post_id,
			];
			if (count($device_tokens) != 0) {
				sendPushNotification($body, $device_tokens, $additional_info);
	            //try {
	              //  Larafirebase::withTitle("Happy Tails TV")
	               // ->withBody("{$request->user()->name} Liked to your comment")
	               // ->sendMessage($device_tokens);
	           // } catch (\Throwable $th) {
	                # Error Sending Notification
	            //}
			}

	         # Add Notification to DB
			Notifications::create([
				'title'=>"Happy Tails TV",
				'notification'=>"{$request->user()->name} liked your comment",
				'user_id'=>$comment->user_id,
				'type'=>'CommentLike',
				'post_id' => $comment->post_id,

			]);
		}

		return [
			'message' => 'success',
			'is_liked' => $user_liked,
			'likes' => count($comment->likes),
		];
	}

	public function replyNew(Request $request)
	{
		$request->validate([
			'comment' => 'required|string',
			'comment_id' => 'required|exists:comments,id'
		]);

		$mainComment = Comment::find($request->comment_id);

		$comment = Comment::create([
			'user_id' => $request->user()->id,
			'comment_id' => $request->comment_id,
			'post_id' => $mainComment->post_id,
			'comment' => $request->comment,
			'is_reply' => 1,
		]);
        // FireBAse Notification
		$body = $request->user()->name . " replied to your comment";
		$device_tokens = DeviceToken::where('user_id', $mainComment->user_id)->pluck('value')->toArray();
		$additional_info = [
			"type" => "Comment",
			"id"  => $mainComment->post_id,
		];
		if (count($device_tokens) != 0) {

			$result =  sendPushNotification($body ,$device_tokens ,$additional_info);
		}
        # Add Notification to DB
		Notifications::create([
			'title'=>"Happy Tails TV",
			'notification'=>"{$request->user()->name} replied to your comment",
			'user_id'=>$mainComment->user_id,
			'type'=>'Comment',
			'post_id' => $mainComment->post_id,
		]);

		return [
			'message' => 'success',
			'messageToken' => $request->messageToken,
			'data' => new CommentResource($comment),
			'total_comments' => count($mainComment->post->comments),
		];
	}

	public function repliesByCommentId(Request $request)
	{
		$request->validate([
			'comment_id' => 'required|exists:comments,id'
		]);

		$comments = DB::table('comments')
		->where('comment_id', $request->post('comment_id'))
		->where('is_reply', 1)
		->select('comments.*', 'users.id AS user_id',
			'users.name', 
			'users.username', 
			'users.profile_picture',
			DB::raw('(SELECT COUNT(*) FROM comment_likes WHERE comment_likes.comment_id=comments.id) AS likes'),
			DB::raw("(SELECT IF(COUNT(*)>0,1,0) FROM comment_likes WHERE comment_likes.comment_id=comments.id AND comment_likes.user_id={$request->user()->id}) AS is_liked")

		)
		->join('users', 'comments.user_id', '=', 'users.id')
            // ->limit()
		->get();



		return [
			'message' => 'success',
			'data' => $comments
		];
	}

	public function commentsByPostId(Request $request, $post)
	{
		$post = Post::find($post);
		if (!$post)
		{
			return [
				'message' => 'error',
				'error' => 'Post does not exist.'
			];
		}

		$comments = $post->comments()->where('is_reply', 0)->latest()->get();

		return [
			'message' => 'success',
			'data' => CommentResource::collection($comments),
			'total_comments' => count($post->comments),
		];
	}

	public function commentsByPostIdGuest($id)
	{
		return [
			'message' => 'success',
			'data' => Comment::where('post_id', $id)->get()
		];
	}
}
