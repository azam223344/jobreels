<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
	public function create(Request $request)
	{
		$request->validate([
			"description" => 'required'
		]);

		$complaint = Complaint::forceCreate([
			'user_id' => $request->user()->id,
			'description' => $request->description
		]);

		return [
			'message' => 'success',
			'data' => $complaint
		];
	}
}
