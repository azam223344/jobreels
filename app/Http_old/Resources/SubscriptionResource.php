<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lastPayment = $this->lastPayment();
        $nextPayment = $this->nextPayment();

        $now = new \DateTime();
        $ends = $this->ends_at ?? $this->paused_from ?? $nextPayment->date ?? null;
        $next_payment_text = '';
        if ($ends !== null)
        {
            $ends = new \DateTime($ends);
            $remaining = $ends->diff($now)->format("%a");
            if ($this->ends_at)
                $next_payment_text = $remaining.' days remaining for expiry';
            else if ($this->paused_from)
                $next_payment_text = $remaining.' days remaining for pausing subscription';
            else if ($nextPayment->date ?? null)
                $next_payment_text = $remaining.' days remaining for next payment';
        }

    	return [
            'type' => 'subscription',
    		'id' => $this->id, 
    		'custom_id' => $this->custom_id,
    		'name' => $this->name,
    		'paddle_id' => $this->paddle_id,
    		'paddle_status' => $this->paddle_status,
    		'paddle_plan' => $this->paddle_plan,
    		'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
    		'receipts' => ReceiptResource::collection($this->whenLoaded('receipts')),
            'last_payment' => $lastPayment,
            'next_payment' => $nextPayment,
            
            'active_or_on_grace' => $this->valid(),
            'active' => $this->active(),
            'on_grace' => $this->onPausedGracePeriod() || $this->onGracePeriod(),
            'past_due' => $this->pastDue(),
            
            'paused' => $this->paused(),
            'on_paused_grace_period' => $this->onPausedGracePeriod(),
            
            'cancelled' => $this->cancelled(),
            'on_grace_period' => $this->onGracePeriod(),
            
            'ended' => $this->ended(),

            'ends_at' => $this->ends_at ? date('Y-m-d H:i:s', strtotime($this->ends_at)) : null,
            'paused_from' => $this->paused_from ? date('Y-m-d H:i:s', strtotime($this->paused_from)) : null,
            
            'paddle_email' => $this->paddleEmail(),
            'payment_method' => $this->paymentMethod(),
            'card_brand' => $this->cardBrand(),
            'card_last_four' => $this->cardLastFour(),
            'card_expiration_date' => $this->cardExpirationDate(),

            'next_payment_text' => $next_payment_text,
    	];
    }
}