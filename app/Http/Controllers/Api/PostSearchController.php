<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostSearchController extends Controller
{
    public function search(Request $request)
    {
        $anyFilter = false;
        $posts = Post::where('is_approved_by_admin',1)
        ->where('active', 1)
        ->where('user_id','!=',165)
        ->with(['user', 'user.freelancer'])
        ->whereHas('user', function($q) {
            $q->where('active', 1)
            ->where('active_publisher', 1);
        });

        // if($request->search){
            $anyFilter = true;
            $posts = $posts->where(function($query) use ($request) {
                if($request->search && !is_numeric($request->search)){

                $query->orWhere('description', 'like',  '%' . $request->search . '%');
                    // ->orWhere('title', 'like',  '%' . $request->search . '%')
                    // ->orWhereHas('user', function($query1) use($request ){
                        // $query1->whereRaw("CONCAT(`first_name`, ' ', `last_name`) like '%" . $request->search . "%'");
                    // })
                    $query->orWhereHas('user.freelancer', function($query1) use($request ){

                        $query1->where(function($q) use ($request) {
                            $q->where( 'description' , 'like' , '%'.$request->search.'%')
                            ->orWhere( 'first_name' , 'like' , '%'.$request->search.'%')
                            ->orWhere( 'last_name' , 'like' , '%'.$request->search.'%');
                        });

                    });
                }
                        // $query1->where('description', 'like',  '%' . $request->search . '%')
                        // // ->orWhere('skills_experience', 'like',  '%' . $request->search . '%')
                        // ->orWhere('first_name', 'like',  '%' . $request->search . '%')
                        // ->orWhere('last_name', 'like',  '%' . $request->search . '%');
                        // ->orWhere('job_title', 'like',  '%' . $request->search . '%');
                        if(isset($request->skills_experience))
		                {
			                $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
                                $query1->where('skills_experience','like' , '%'.$request->skills_experience.'%');});
		                }
                        if(isset($request->availablity))
		                {
			                $availablity = ($request->availablity == "Either" || $request->availablity == "Any") ? ["Part time","Full time","Either","Any"] : [$request->availablity];
			                $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
                                    $query1->whereIn('full_time',$availablity);});
		                }
		                // if(isset($request->skills))
		                // {
			            //     $query1 = $query1->where( 'skills_assessment' , 'like' , '%'.$request->skills.'%');
		                // }
                        if($request->years_experience == "Less than 1 year")
		                {
			                $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
                                $query1->whereIn('years_experience',['Fresh',$request->years_experience]);});
		                }
		                if($request->years_experience == "1-2 years")
		                {
			                $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
                                $query1->whereIn('years_experience',['1 year','2 years','1-2 years'])->whereNotNull('years_experience');});
		                }
                        if($request->years_experience == "3+ years")
		                {
			                $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
                                $query1->whereNotIn('years_experience',['Fresh','Less than 1 year','1 year','2 years'])->whereNotNull('years_experience');});
		                }

//                if($request->salary_requirements)
//                {
//                    $query = $query->orWhereHas('user.freelancer', function($query1) use($request ){
//                        $query1->whereIn('salary_requirements','>=',$request->salary_requirements);
//                    });
//                }
                        return $query;
                    });
                // });
            // where(function($query) use ($request) {
            //     $query->where('description', 'like',  '%' . $request->search . '%')
            //     ->orWhere('title', 'like',  '%' . $request->search . '%')
            //     ->orWhere('user.description', 'like',  '%' . $request->search . '%');
            // });
        // }

        if($request->min_hourly_rate){
            $anyFilter = true;
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('hourly_rate', '>=', $request->min_hourly_rate);
            });
        }
        if($request->salary_requirements)
        {
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('salary_requirements','>=',$request->salary_requirements);
            });


        }
        if (is_numeric($request->search)){

            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('salary_requirements','>=',$request->search);
            });
        }


        if($request->max_hourly_rate){
            $anyFilter = true;
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('hourly_rate', '<=', $request->max_hourly_rate);
            });
        }

        if($request->availability){
            $anyFilter = true;
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('full_time', $request->availability);
            });
        }

        if($request->verified){
            $anyFilter = true;
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('verification_level', $request->verified);
            });
        }

        if($request->skills){
            $anyFilter = true;
            // dd($request->skills);
            $posts = $posts->where('skills', 'LIKE','%' . $request->skills . '%');
        }

        if($request->experience)
        {
            $anyFilter = true;
            $posts = $posts->whereHas('user.freelancer', function($query) use($request ){
                $query->where('years_experience', $request->experience);
            });
        }
        //dd($posts->toSql());

        $posts = $posts->orderByDesc('updated_at')->get();

        return [
            'message' => 'success',
            'data' => PostResource::collection($posts),
        ];
    }
      public function searchMobile(Request $request)
    {
        $posts = Post::where('is_approved_by_admin',1)
        ->where('active', 1)
        ->with('user')
        ->whereHas('user', function($q) {
            $q->where('active', 1)
            ->where('active_publisher', 1);
        });

        if($request->state_id != -1){
            $posts = $posts->where('state_id', $request->state_id);
        }

        if($request->breed_id != -1 ){
            $posts = $posts->where('breed_id', $request->breed_id);
        }

        if($request->sex != -1){
            $posts = $posts->where('sex', $request->sex);
        }

        if($request->age != -1){
            $posts = $posts->where('age', $request->age);
        }

        if($request->energy_level != -1){
            $posts = $posts->where('energy_level', $request->energy_level);
        }

        if($request->size != -1 ){
            $posts = $posts->where('size', $request->size);
        }

        if($request->color_id != -1){
            $posts = $posts->where('color_id', $request->color_id);
        }

        // if($request->search != -1 ){
        //     $posts = $posts->where('description', 'like',  '%' . $request->search . '%');
        // }

        $posts = $posts->get();

        return [
            'message' => 'success',
            'data' => PostResource::collection($posts),
        ];
    }
    public function searchGuest(Request $request)
    {
        $posts = Post::where('is_approved_by_admin',1);

        if($request->state_id){
            $posts = $posts->where('state_id', $request->state_id);
        }

        if($request->sex){
            $posts = $posts->where('sex', $request->sex);
        }

        if($request->age){
            $posts = $posts->where('age', $request->age);
        }

        if($request->energy_level){
            $posts = $posts->where('energy_level', $request->energy_level);
        }

        if($request->size){
            $posts = $posts->where('size', $request->size);
        }

        if($request->breed_id){
            $posts = $posts->where('breed_id', $request->breed_id);
        }

        if($request->color_id){
            $posts = $posts->where('color_id', $request->color_id);
        }

        if($request->search){
            $posts = $posts->where('description', 'like',  '%' . $request->search . '%');
        }

        $posts = $posts->get();

        return [
            'message' => 'success',
            'data' => PostResource::collection($posts),
        ];
    }
}
