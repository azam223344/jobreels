<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\DeviceToken;
use App\Models\User;
use App\Models\PostSave;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Models\Notifications;

class PostSavesController extends Controller
{
	public function save(Request $request, $post)
	{
		$post = Post::find($post);
		if (!$post)
		{
			return [
				'message' => 'error',
				'error' => 'Post does not exist.'
			];
		}

		$user_saved = false;

		$oldRecord = PostSave::where('post_id', $post->id)->where('user_id', $request->user()->id)->first();

		if (!$oldRecord)
		{
			PostSave::forceCreate([
				'user_id' => $request->user()->id,
				'post_id' => $post->id,
			]);

			$user_saved = true;
		}
		else
		{
			$oldRecord->delete();

			$user_saved = false;
		}

		if ($user_saved && $request->user()->id != $post->user_id)
		{
	        // FireBAse Notification
			$body = $request->user()->first_name." ".$request->user()->last_name." saved your post";
			$device_tokens = DeviceToken::where('user_id', $post->user_id)->pluck('value')->toArray();

			$additional_info = [
				"type" => "Save",
				"id"  => $post->id,
			];
			if (count($device_tokens) != 0) {
				$result = sendPushNotification($body ,$device_tokens ,$additional_info);
			}

			Notifications::create([
				'title'=>"JobReels",
				'notification'=>"{$request->user()->first_name} {$request->user()->last_name} saved your post",
				'user_id'=>$post->user_id,
				'type'=> 'Save',
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
			'user_saved' => $user_saved,
			'total_saves' => count($post->saves),
		];
	}
}
