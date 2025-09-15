<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Followings;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $skills = json_decode($this->skills, true);
        if (is_null($skills))
        {
            $skills = array($this->skills);
        }
        return [
            'id' => $this->id,
            'self_post' => auth('api')->check() ? auth('api')->id() == $this->user_id : false,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'portfolio' => $this->portfolio,
            'skill' => $this->skills,
            'skills' =>$skills,
            'upwork' => $this->upwork,
            'fiverr' => $this->fiverr,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'youtube' => $this->youtube,
            'tiktok' => $this->tiktok,
            'twitter' => $this->twitter,
            'thumbnail' => url('/uploads/'.$this->thumbnail),
            'video' => $this->video_url,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'active' => $this->active,
            'is_featured' => $this->is_featured,
            'is_approved_by_admin' => $this->is_approved_by_admin,
            'user_liked' => auth('api')->check() ? $this->likes()->where('user_id', auth('api')->id())->exists() : false,
            'user_saved' => auth('api')->check() ? $this->saves()->where('user_id', auth('api')->id())->exists() : false,
            'user_followed' => auth('api')->check() ? Followings::where('follower_id', auth('api')->id())->where('user_id', $this->user_id)->exists() : false,
            'total_likes' => count($this->likes),
            'total_saves' => count($this->saves),
            'total_comments' => count($this->comments),
            'user' => $this->user ? new UserResource($this->user) : null,
        ];
    }
}
