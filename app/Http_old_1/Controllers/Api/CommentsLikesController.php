<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
// use App\Models\Replies;
use App\Models\Post;
use App\Models\DeviceToken;
use App\Models\Likes;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CommentsLikesController extends Controller
{
	
	function likecomment(Request $request){


		$request->validate([
            // 'comment' => 'required',
			'comment_id' => 'required|exists:comments,id'
		]);

        # Skip if Like already exists
		$is_liked = DB::table('comment_likes')
		->where('comment_id',$request->comment_id)
		->where('user_id',$request->user()->id)->count();
		if ($is_liked) {
			return [
				'message' => 'success',
				'data' => []
			];
		}

		$like = Likes::create([
			'user_id' => $request->user()->id,
			'comment_id' => $request->comment_id,
		]);



        // $body = $request->user()->name . "Commented On Your Post";
        //         $device_tokens = DeviceToken::where('user_id', $postUser->user_id)->pluck('value')->toArray();
        //         $additional_info = [
        //             "type" => "CommentLike",
        //             "id"  => $request->post_id,
        //         ];
        //         if (count($device_tokens) != 0) {
        //             sendPushNotification($body, $device_tokens, $additional_info);
        //         }

		return [
			'message' => 'success',
			'data' => $like
		];

	}

}
