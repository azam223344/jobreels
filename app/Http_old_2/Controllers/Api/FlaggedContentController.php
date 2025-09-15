<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlagedContent;
use Illuminate\Http\Request;

class FlaggedContentController extends Controller
{
	public function flag(Request $request)
	{
		$request->validate([
			'post_id' => 'required|exists:posts,id',
			'flag_id' => 'required|exists:flags,id',
			//'description' => 'required'
		]);

		if (FlagedContent::where('post_id', $request->post_id)
			->where('flag_id', $request->flag_id)
			->where('user_id', $request->user()->id)
			->exists())
		{
			return [
				'message' => 'error',
				'error' => 'You have already reported this post in selected category'
			];
		}

		$record = FlagedContent::forceCreate([
			'post_id' => $request->post_id,
			'flag_id' => $request->flag_id,
			'description' => $request->description ?? NULL,
			'user_id' => $request->user()->id
		]);

		if ($record)
		{
			return [
				'message' => 'success',
			];
		}
		else
		{
			return [
				'message' => 'error',
				'error' => 'Post not reported'
			];
		}
	}
}
