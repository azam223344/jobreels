<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserFlag;
use Illuminate\Http\Request;
use App\Http\Resources\FlagResource;

class UserFlagController extends Controller
{
	public function index(Request $request)
	{
		$flags = UserFlag::where('active', 1)->orderby('name')->get();

		return [
			'message' => 'success',
			'data' => FlagResource::collection($flags)
		];
	}
}
