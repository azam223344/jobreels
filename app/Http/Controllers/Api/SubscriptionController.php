<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\SubscriptionResource;
use App\Package;
use Laravel\Cashier\Cashier;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class SubscriptionController extends Controller
{

    public function subscribe(Request $request){
        $request->validate([
            //'plan_id' => 'required|string|in:753821,753820,753819,753818',
            'plan_id' => 'required|string|in:32388,32392',
        ]);

        if (auth('api')->user()->subscriptions()->active()->exists())
        {
            return response([
                'status' => 'error',
                'message' => 'You have already subscribed.',
            ], 422);
        }
        $plans = [
            // sandbox
            '32388' => 'price_1OSQKtGGTzKs4xvDw65cxV6j',
            '32392' => 'price_1OSQLQGGTzKs4xvDMnusokUE',
        ];

        auth('api')->user()->newSubscription(
           'default' ,$plans[$request->plan_id]
        )->create($request->paymentMethodId);
        $user=new User();

        return response(['success'=>true,'message'=>'User Subscribed Successfully....']);



    }

    public function plans(){
        $plans = [
            // sandbox
            ['code'=>'32388', 'price_id'=>'price_1OSQKtGGTzKs4xvDw65cxV6j','type'=>'Monthly','price'=>28.99
                //,'live_price_id'=>'price_1R6MTHGGTzKs4xvDlOt9D0oB'
                ],
            ['code'=>'32392', 'price_id'=>'price_1OSQLQGGTzKs4xvDMnusokUE','type'=>'Yearly','price'=>173.99,
              //,'live_price_id'=>'price_1R6MTHGGTzKs4xvDlOt9D0oB
                ],
        ];
        return response([$plans]);
    }
	public function get_link(Request $request)
	{



//		if (auth('api')->user()->subscriptions()->active()->exists())
//		{
//			return response([
//				'status' => 'error',
//				'message' => 'You have already subscribed.',
//			], 422);
//		}

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


        $intent=auth('api')->user()->createSetupIntent();

        return response()->json([
            'clientSecret'=>$intent->client_secret
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
		$subscriptions = auth('api')->user()->subscription();

		return [
			'status' => 'success',
			'is_subscribed' => auth('api')->user()->subscriptions()->active()->exists(),
			'trial_ends_at' => auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null,
			'subscriptions' => SubscriptionResource::collection($subscriptions),
		];
	}

	public function current(Request $request)
	{
		$subscription = auth('api')->user()->subscription();

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
			'is_subscribed' => auth('api')->user()->subscription()->active(),
			'trial_ends_at' => auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null,
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function change_plan(Request $request)
	{
		$request->validate([
			//'plan_id' => 'required|string|in:753821,753820,753819,753818',
			'plan_id' => 'required|string|in:32388,32392',
		]);

        $plans = [
            // sandbox
            '32388' => 'price_1OSQKtGGTzKs4xvDw65cxV6j',
            '32392' => 'price_1OSQLQGGTzKs4xvDMnusokUE',
        ];

        $subscription = auth('api')->user()->subscription();

		if (!$subscription)
		{
			return response([
				'status' => 'error',
				'message' => 'Not subscribed.',
			], 422);
		}

		try
		{
			$subscription->swapAndInvoice($plans[$request->plan_id]);
			$subscription->forceFill(['name' => $plans[$request->plan_id]])->save();
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
		$subscription = auth('api')->user()->subscription();

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
			'is_subscribed' => auth('api')->user()->subscription()->active(),
            'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function resume(Request $request)
	{
		$subscription = auth('api')->user()->subscription();

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
			'is_subscribed' => auth('api')->user()->subscription()->active(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function cancel(Request $request)
	{
		$subscription = auth('api')->user()->subscription();

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
			'is_subscribed' => auth('api')->user()->subscription()->active(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}

	public function force_cancel(Request $request)
	{
		$subscription = auth('api')->user()->subscription();

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
			'is_subscribed' => auth('api')->user()->subscription()->active(),
			'subscription' => new SubscriptionResource($subscription),
		];
	}


    public function paymentIntent(Request $request){
//        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        // Create a payment intent
        $intent = PaymentIntent::create([
            'amount' => 12.34*100, // Amount in cents (adjust as needed)
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            // Add additional parameters as required by your application
        ]);

        // Return the client secret to your Flutter app
        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }

}
