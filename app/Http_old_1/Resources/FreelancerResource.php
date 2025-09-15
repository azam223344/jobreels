<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerResource extends JsonResource
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
    		'user_id' => $this->user_id,
            'photo' => $this->photo ? url('/uploads/'.$this->photo) : url('/uploads/default.png'),
            'photo_of_govt_id' => url('/uploads/'.$this->photo_of_govt_id),
            'photo_of_govt_id_back' => url('/uploads/'.$this->photo_of_govt_id_back),
            'photo_with_govt_id' => url('/uploads/'.$this->photo_with_govt_id),
            'bills' => url('/uploads/'.$this->bills),
            'portfolio_website' => $this->portfolio_website,
            'description' => $this->description,
            'salary_requirements' => $this->salary_requirements,
            'full_time' => $this->full_time,
            'hourly_rate' => $this->hourly_rate,
            'skills_experience' => $this->skills_experience,
            'skills_assessment' => json_decode($this->skills_assessment),
            'upwork' => $this->upwork,
            'fiverr' => $this->fiverr,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'youtube' => $this->youtube,
            'tiktok' => $this->tiktok,
            'twitter' => $this->twitter,
            'verification_level' => $this->verification_level,
            'date_of_birth' => date('Y-m-d', strtotime($this->date_of_birth)),
            'age' => (new \DateTime())->diff(new \DateTime($this->date_of_birth))->y,
            'gender' => $this->gender,
            'years_experience' => $this->years_experience,
            'verification_score' => $this->verification_score,
            'job_title' => $this->job_title,
        ];
    }
}
