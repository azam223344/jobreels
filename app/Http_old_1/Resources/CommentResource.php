<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'created_at_relative' => \App\Helper\Helper::relativeTime(time(), strtotime($this->created_at), true),
            'updated_at' => $this->updated_at,
            'likes' => count($this->likes),
            'is_liked' => auth('api')->check() ? $this->likes()->where('user_id', auth('api')->id())->exists() : false,
            'replies' => CommentResource::collection($this->replies),
            'user' => $this->user ? new UserResource($this->user) : null,
        ];
    }
}
