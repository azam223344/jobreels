<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Jobs\SendEmailJob;
use App\Http\Resources\TransactionResource;

class TransactionsController extends Controller
{
	public function create(Request $request)
	{
		$request->validate([
			'post_id' => 'required|exists:posts,id',
			// 'gateway_information' => 'required',
			// 'amount' => 'required'
			'name' => 'required|string',
			'email' => 'required|email',
			'phone' => 'required|string',
		]);

		$adopter = $request->user();
		$post = Post::find($request->post_id);
		$shelter = $post->user;

		$transaction = Transaction::forceCreate([
			'post_id' => $request->post_id,
            //'gateway_information' => $request->gateway_information,
            //'amount' => $request->amount,
			'shelter_user_id' => $shelter->id,
			'adopter_user_id' => $adopter->id,
			'name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
		]);

		Notifications::create([
			'title' => "Happy Tails TV",
			'notification' => "{$adopter->name} requested to adopt.",
			'user_id' => $shelter->id,
			'type' => 'Adoption',
			'post_id' =>  $post->id,
		]);

  # Send Email Notification
		SendEmailJob::dispatchAfterResponse(new SendEmailJob([
			'to' => $shelter->email,
			'title' => 'Alert | Happy tails TV',
			'body' => "{$adopter->name} has requested against this {$post->name}",
			'subject' => 'Alert | Happy tails TV'
		]));
		return [
			'message' => 'success',
			'data' => new TransactionResource($transaction),
		];
	}

	public function requests(Request $request)
	{
		$transactions = Transaction::where('shelter_user_id', $request->user()->id)->latest()->get();
		
		return [
			'message' => 'success',
			'data' => TransactionResource::collection($transactions),
		];
	}

	public function requested(Request $request)
	{
		$transactions = Transaction::where('adopter_user_id', $request->user()->id)->latest()->get();
		
		return [
			'message' => 'success',
			'data' => TransactionResource::collection($transactions),
		];
	}
}
