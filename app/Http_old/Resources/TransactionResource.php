<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'shelter_user_id' => $this->shelter_user_id,
            'adopter_user_id' => $this->adopter_user_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'created_at_formatted' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'created_at_relative' => \App\Helper\Helper::relativeTime(time(), strtotime($this->created_at), true),
            'updated_at' => $this->updated_at,
            'post' => $this->postRel ? new PostResource($this->postRel) : null,
            'shelter_user' => $this->shelterRel ? new UserResource($this->shelterRel) : null,
            'adopter_user' => $this->adopterRel ? new UserResource($this->adopterRel) : null,
        ];
    }
}
