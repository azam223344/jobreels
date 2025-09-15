<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Resources\PackageResource;
use App\Http\Resources\SubscriptionResource;

class PackageController extends Controller
{
    public function list(Request $request)
    {
        $packages = Package::where('active', true)->orderby('name')->first();
        $subscription = auth('api')->user() ? auth('api')->user()->subscriptions()->active()->latest()->first() : null;
        
        return [
            'message' => 'success',
            'data' => new PackageResource($packages),
            'is_subscribed' => auth('api')->user() ? auth('api')->user()->subscriptions()->active()->exists() : false,
            'trial_ends_at' => auth('api')->user() ? (auth('api')->user()->onTrial() && auth('api')->user()->customer ? date('Y-m-d H:i:s', strtotime(auth('api')->user()->customer->trial_ends_at)) : null) : null,
            'current_subscription' => $subscription ? new SubscriptionResource($subscription) : null,
        ];
    }

    public function delete(Request $request)
    {
        Package::where('id', $request->package_id)->update(['active' => false]);
        return \Redirect::back()->with('success', 'Successfully');
    }
}
