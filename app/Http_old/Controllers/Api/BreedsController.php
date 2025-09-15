<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Http\Request;
use App\Http\Resources\BreedResource;

class BreedsController extends Controller
{
	public function list(Request $request)
	{
		$breeds = Breed::where('active', 1)->orderby('name')->get();
		
		return [
			'message' => 'success',
			'data' => BreedResource::collection($breeds)
		];
	}
	
	public function delete(Request $request)
	{
		Breed::where('id', $request->breed_id)->delete();
		return \Redirect::back()->with('success', 'Successfully');
	}
}
