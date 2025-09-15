<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, 
            'checkout_id' => $this->checkout_id,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'currency' => $this->currency,
            'quantity' => $this->quantity,
            'receipt_url' => $this->receipt_url,
            'paid_at' => date('Y-m-d H:i:s', strtotime($this->paid_at)),
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
