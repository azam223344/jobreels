<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\{PostLike, DeviceToken, User};
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Models\Notifications;

class PostLikesController extends Controller
{
	public function like(Request $request, $post)
	{
		$post = Post::find($post);
		if (!$post)
		{
			return [
				'message' => 'error',
				'error' => 'Post does not exist.'
			];
		}

		$user_liked = false;

		$oldRecord = PostLike::where('post_id', $post->id)->where('user_id', $request->user()->id)->first();

		if (!$oldRecord) 
		{
			PostLike::forceCreate([
				'user_id' => $request->user()->id,
				'post_id' => $post->id,
			]);

			$user_liked = true;
		} 
		else
		{
			$oldRecord->delete();
			
			$user_liked = false;
		}

		if ($user_liked && $request->user()->id != $post->user_id)
		{
	        // FireBAse Notification
			$body = $request->user()->name." liked your post";
			$device_tokens = DeviceToken::where('user_id', $post->user_id)->pluck('value')->toArray();

			$additional_info = [
				"type" => "PostLike",
				"id"  => $post->id,
			];
			if (count($device_tokens) != 0) {
				$result = sendPushNotification($body ,$device_tokens ,$additional_info);
			}

			Notifications::create([
				'title'=>"Happy Tails TV",
				'notification'=>"{$request->user()->name} liked your post",
				'user_id'=>$post->user_id,
				'type'=> 'PostLike',
				'post_id' =>  $post->id,
			]);

	        # Send Email Notification
	       // SendEmailJob::dispatchAfterResponse(new SendEmailJob([
	         //   'to' => $email->email,
	          //  'title' => 'Alert | Happy tails TV',
	           // 'body' => "{$request->user()->name} Liked Your Post;",
	           // 'subject' => 'Alert | Happy tails TV'
	       // ]));
		}

		return [
			'message' => 'success',
			'user_liked' => $user_liked,
			'total_likes' => count($post->likes),
		];
	}
}
