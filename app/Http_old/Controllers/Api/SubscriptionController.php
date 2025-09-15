<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubscriptionResource;
use App\Package;

class SubscriptionController extends Controller
{
	public function get_link(Request $request)
	{		
		$request->validate([
			//'plan_id' => 'required|string|in:753821,753820,753819,753818',
			'plan_id' => 'required|string|in:32388,32392',
			'return_url' => 'required|string',
		]);

		// $payLink = auth('api')->user()->chargeProduct($request->plan_id, [
		// 	'return_url' => $request->return_url.'?checkout={checkout_hash}'
		// ]);

		// return response([
		// 	'status' => 'success',
		// 	'payLink' => $payLink,
		// ]);

		if (auth('api')->user()->subscriptions()->active()->exists())
		{
			return response([
				'status' => 'error',
				'message' => 'You have already subscribed.',
			], 422);
		}

		/*
		Live
		753821: Test daily 1 USD
		753818: Realtor License
		753819: Creator License
		753820: Commercial License

		Sandbox
		23506: Test daily 1 USD
		23507: Realtor License Yearly
		23508: Creator License Yearly
		23509: Commercial License Yearly
		24615: Realtor License Monthly
		24614: Creator License Monthly
		24613: Commercial License Monthly
		*/

		$plans = [
			// live
			// '753821' => 'test',
			// '753818' => 'realtor',
			// '753819' => 'creator',
			// '753820' => 'commercial',

			// sandbox
			'32388' => 'monthly',
			'32392' => 'yearly',
		];

		$payLink = auth('api')->user()->newSubscription($plans[$request->plan_id.''], $request->plan_id)
		->returnTo($request->return_url)
		->create();

		return response([
			'status' => 'success',
			'payLink' => $payLink,
		]);
	}

	public function details_checkout_id(Request $request, $checkout_id)
	{
		$receipt = auth('api')->user()->receipts()->where('checkout_id', $checkout_id)->first();
		
		if(!$receipt)
		{
			return response([
				'status' => 'error',
				'message' => 'No subscription found',
			], 422);
		}

		$subscription = $receipt->subscription;
		//$subscription->load('receipts');

		return [
			'status' => 'success',
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function my_subscriptions(Request $request)
	{
		$subscriptions = auth('api')->user()->subscriptions()->get();
		
		return [
			'status' => 'success',
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'trial_ends_at' => auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null,
			'subscriptions' => SubscriptionResource::collection($subscriptions),
		];
	}

	public function current(Request $request)
	{
		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return [
				'status' => 'success',
				'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
				'trial_ends_at' => auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null,
				'subscription' => null,
			];
		}

		return [
			'status' => 'success',
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'trial_ends_at' => auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null,
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function change_plan(Request $request)
	{
		$request->validate([
			//'plan_id' => 'required|string|in:753821,753820,753819,753818',
			'plan_id' => 'required|string|in:23506,23507,23508,23509',
			'return_url' => 'required|string',
		]);

		$plans = [
			// live
			// '753821' => 'test',
			// '753818' => 'realtor',
			// '753819' => 'creator',
			// '753820' => 'commercial',

			// sandbox
			'23506' => 'test',
			'23507' => 'realtor',
			'23508' => 'creator',
			'23509' => 'commercial',
		];

		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}

		try
		{
			$subscription->swapAndInvoice($request->plan_id);
			$subscription->forceFill(['name' => $plans[$request->plan_id.'']])->save();
		}
		catch(\Exception $e)
		{
			return response([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 422);
		}

		return [
			'status' => 'success', 
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function pause(Request $request)
	{
		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}

		try
		{
			$response = $subscription->pause();
		}
		catch(\Exception $e)
		{
			return response([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 422);
		}

		return [
			'status' => 'success', 
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
            'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function resume(Request $request)
	{
		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}
		
		try
		{
			$response = $subscription->unpause();
		}
		catch(\Exception $e)
		{
			return response([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 422);
		}
		
		return [
			'status' => 'success', 
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function cancel(Request $request)
	{
		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}
		
		try
		{
			$response = $subscription->cancel();
		}
		catch(\Exception $e)
		{
			return response([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 422);
		}
		
		// try {
		// 	\Mail::to(auth('api')->user())->send(new \App\Mail\SubscriptionEnded(auth('api')->user(), 'cancelled'));
		// } catch(\Exception $e) { }

		return [
			'status' => 'success', 
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function force_cancel(Request $request)
	{
		$subscription = auth('api')->user()->subscriptions()->first();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}
		
		try
		{
			$response = $subscription->cancelNow();
		}
		catch(\Exception $e)
		{
			return response([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 422);
		}
		
		// try {
		// 	\Mail::to(auth('api')->user())->send(new \App\Mail\SubscriptionEnded(auth('api')->user(), 'cancelled'));
		// } catch(\Exception $e) { }
		
		return [
			'status' => 'success', 
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}

}
