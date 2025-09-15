<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Resources\StateResource;

class StatesController extends Controller
{
	public function list(Request $request)
	{
		$states = State::where('country', $request->country ?? 'America')->orderby('state')->orderby('city')->groupBy('state')->get();
		
		return [
			'message' => 'success',
			'data' => StateResource::collection($states)
		];
	}

	public function delete(Request $request)
	{
		State::where('id', $request->state_id)->delete();
		return \Redirect::back()->with('success', 'Successfully');
	}
}
