<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Followings;

class PostSaveResource extends JsonResource
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
            'id' => $this->post->id,
            'self_post' => auth('api')->check() ? auth('api')->id() == $this->post->user_id : false,
            'user_id' => $this->post->user_id,
            'title' => $this->post->title,
            'description' => $this->post->description,
            'portfolio' => $this->post->portfolio,
            'skill' => $this->post->skills,
            'upwork' => $this->post->upwork,
            'fiverr' => $this->post->fiverr,
            'linkedin' => $this->post->linkedin,
            'instagram' => $this->post->instagram,
            'facebook' => $this->post->facebook,
            'youtube' => $this->post->youtube,
            'tiktok' => $this->post->tiktok,
            'twitter' => $this->post->twitter,
            'thumbnail' => url('/uploads/'.$this->post->thumbnail),
            'video' => $this->post->video_url,
            'status' => $this->post->status,
            'status_description' => $this->post->status_description,
            'active' => $this->post->active,
            'is_featured' => $this->post->is_featured,
            'is_approved_by_admin' => $this->post->is_approved_by_admin,
            'user_liked' => auth('api')->check() ? $this->post->likes()->where('user_id', auth('api')->id())->exists() : false,
            'user_saved' => auth('api')->check() ? $this->post->saves()->where('user_id', auth('api')->id())->exists() : false,
            'user_followed' => auth('api')->check() ? Followings::where('follower_id', auth('api')->id())->where('user_id', $this->post->user_id)->exists() : false,
            'total_likes' => count($this->post->likes),
            'total_saves' => count($this->post->saves),
            'total_comments' => count($this->post->comments),
            'user' => $this->post->user ? new UserResource($this->post->user) : null,
        ];
    }
}
