<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Resources\ColorResource;

class ColorsController extends Controller
{
	public function list(Request $request)
	{
		$colors = Color::where('active', 1)->orderby('name')->get();
		
		return [
			'message' => 'success',
			'data' => ColorResource::collection($colors)
		];
	}
}
