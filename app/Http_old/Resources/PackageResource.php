<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $arr = [
            'id' => $this->id,
            'order' => $this->order,
            'name' => $this->name,
            'tagline' => $this->tagline,
            'paddle_id_monthly' => $this->paddle_id_monthly,
            'amount_monthly' => $this->amount_monthly,
            'paddle_id_annually' => $this->paddle_id_annually,
            'amount_annually' => $this->amount_annually,
            'details' => explode("\n", $this->details),
            'amount_monthly_12' => number_format($this->amount_monthly * 12, 2),
            'amount_diff' => number_format(($this->amount_monthly * 12) - $this->amount_annually, 2),
            'active' => $this->active,
            'user_logged_in' => auth('api')->user() ? true : false,
        ];
        
        if (auth('api')->user())
        {
            if ($this->paddle_id_monthly)
                $arr['pay_link_monthly'] = auth('api')->user()->newSubscription('monthly', $this->paddle_id_monthly)->create();
            if ($this->paddle_id_annually)
                $arr['pay_link_annually'] = auth('api')->user()->newSubscription('annually', $this->paddle_id_annually)->create();
        }

        return $arr;
    }
}
