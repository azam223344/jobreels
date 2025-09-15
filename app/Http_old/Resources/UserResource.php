<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Followings;
use App\Models\PostLike;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $subscription = $this->subscriptions()->active()->latest()->first();

    	return [
            'id' => $this->id,
    		'uuid' => $this->uuid,
            'description' => $this->description,
            'business_name' => $this->business_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'industry' => $this->industry,
            'employee_id' => $this->employee_id,
            'active' => $this->active,
            'profile_picture' => $this->profile_picture ? url('/uploads/'.$this->profile_picture) : url('/uploads/default.png'),
            'active_publisher' => $this->active_publisher,
            'type' => $this->type,
            'total_followings' => count($this->followings),
    		'total_followers' => count($this->followers),
            'user_followed' => auth('api')->check() ? Followings::where('follower_id', auth('api')->id())->where('user_id', $this->id)->exists() : false,
    		'total_likes' => PostLike::with('post')->whereHas('post', function($q) { $q->where('user_id', $this->id); })->count(),
            'freelancer' => $this->freelancer ? new FreelancerResource($this->freelancer) : null,
    		'created_at' => $this->created_at,
    		'updated_at' => $this->updated_at,
            'is_subscribed' => $subscription ? true : false,
            'is_phone_private' => $this->is_phone_private,
            'is_email_private' => $this->is_email_private,
            'trial_ends_at' => (auth('api')->check() && auth('api')->id() == $this->id ? ($this->onTrial() && $this->customer ? date('Y-m-d H:i:s', strtotime($this->customer->trial_ends_at)) : null) : null),
            'current_subscription' => (auth('api')->check() && auth('api')->id() == $this->id ? ($subscription ? new SubscriptionResource($subscription) : null) : null),
		];
	}
}
