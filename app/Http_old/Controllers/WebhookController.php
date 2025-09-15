<?php

namespace App\Http\Controllers;

use Laravel\Paddle\Http\Controllers\WebhookController as CashierController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Events\PaymentSucceeded;
use Laravel\Paddle\Events\SubscriptionCancelled;
use Laravel\Paddle\Events\SubscriptionCreated;
use Laravel\Paddle\Events\SubscriptionPaymentFailed;
use Laravel\Paddle\Events\SubscriptionPaymentSucceeded;
use Laravel\Paddle\Events\SubscriptionUpdated;
use Laravel\Paddle\Events\WebhookHandled;
use Laravel\Paddle\Events\WebhookReceived;
use Laravel\Paddle\Exceptions\InvalidPassthroughPayload;
use Laravel\Paddle\Http\Middleware\VerifyWebhookSignature;
use Laravel\Paddle\Subscription;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierController
{
    protected function handleSubscriptionCreated(array $payload)
    {
        $passthrough = json_decode($payload['passthrough'], true);

        if (! is_array($passthrough) || ! isset($passthrough['subscription_name'])) {
            throw new InvalidPassthroughPayload;
        }

        $customer = $this->findOrCreateCustomer($payload['passthrough']);

        $trialEndsAt = $payload['status'] === Subscription::STATUS_TRIALING
        ? Carbon::createFromFormat('Y-m-d', $payload['next_bill_date'], 'UTC')->startOfDay()
        : null;

        $subscription = $customer->subscriptions()->create([
            'name' => $passthrough['subscription_name'],
            'paddle_id' => $payload['subscription_id'],
            'paddle_plan' => $payload['subscription_plan_id'],
            'paddle_status' => $payload['status'],
            'quantity' => $payload['quantity'],
            'trial_ends_at' => $trialEndsAt,
        ]);

        // $subscription->update([
        //     'custom_id' => 'SUB-'.$payload['subscription_id'].'-'.strtoupper(substr(md5(time().mt_rand(0, 1000)), 0, 6))
        // ]);

        // $package = Package::where('paddle_id_annually', $payload['subscription_plan_id'])->first();

        // try {
        //     \Mail::to($customer)->send(new \App\Mail\SubscriptionPurchased($customer, $package));
        // } catch(\Exception $e) { }

        SubscriptionCreated::dispatch($customer, $subscription, $payload);
    }
}