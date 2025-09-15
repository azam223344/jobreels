<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use Illuminate\Http\Request;
use App\Http\Resources\FlagResource;

class FlagController extends Controller
{
	public function index(Request $request)
	{
		$flags = Flag::where('active', 1)->orderby('name')->get();

		return [
			'message' => 'success',
			'data' => FlagResource::collection($flags)
		];
	}
}
