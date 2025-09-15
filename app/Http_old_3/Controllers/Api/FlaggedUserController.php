<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlagedUser;
use Illuminate\Http\Request;

class FlaggedUserController extends Controller
{
	public function flag(Request $request)
	{
		$request->validate([
			'reported_user_id' => 'required|exists:users,id',
			'flag_id' => 'required|exists:flags,id',
			//'description' => 'required'
		]);

		if (FlagedUser::where('reported_user_id', $request->reported_user_id)
			->where('flag_id', $request->flag_id)
			->where('user_id', $request->user()->id)
			->exists())
		{
			return [
				'message' => 'error',
				'error' => 'You have already reported this user in selected category'
			];
		}

		$record = FlagedUser::forceCreate([
			'reported_user_id' => $request->reported_user_id,
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
				'error' => 'User not reported'
			];
		}
	}
}
