<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Post,User,DeviceToken};
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Storage;
use App\Models\Notifications;
use Illuminate\Support\Facades\{DB,Validator,Http,Mail};
use App\Models\Breed;
use App\Models\Color;
use App\Models\State;
use App\Http\Resources\PostResource;
use App\Models\{Threads, Messages};

class PostsController extends Controller
{
    public function dropdowns(Request $request)
    {
        return [
            'message' => 'success',
        ];
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'portfolio' => 'nullable|url|max:191',
            'skill' => 'required|string|max:191',
            'upwork' => 'nullable|string|max:191',
            'fiverr' => 'nullable|string|max:191',
            'linkedin' => 'nullable|string|max:191',
            'youtube' => 'nullable|string|max:191',
            'instagram' => 'nullable|string|max:191',
            'facebook' => 'nullable|string|max:191',
            'tiktok' => 'nullable|string|max:191',
            'twitter' => 'nullable|string|max:191',
            'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mov',
            'thumbnail' => 'nullable|image',
        ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->getMessages()], 403);
    }
        $user = $request->user();
        $userPostCount = Post::where('user_id', $user->id)->count();
        $status = 'published';
        if($userPostCount == 0){
            $status = 'under-review';
        }

        $request->merge([
            'user_id'=> $user->id,
            'status' => $status,
            'status_description' => '',
            "active" => 1,
            "is_featured" => 0,
            "is_approved_by_admin" => 1,
        ]);

        $arr = $request->only([
            'user_id',
            'title',
            'description',
            'portfolio',
            'upwork',
            'fiverr',
            'linkedin',
            'instagram',
            'facebook',
            'youtube',
            'tiktok',
            'twitter',
            'status',
            'status_description',
            'active',
            'is_featured',
            'is_approved_by_admin',
        ]);

        $arr['video'] = $this->storeVideo($request->video);
        $arr['thumbnail'] = $request->has('thumbnail') ? $request->thumbnail->store('/thumbnail') : null;
        $arr['skills'] = $request->skill;
        $post = Post::forceCreate($arr);
        
          // FireBAse Notification
       // $email = User::where('id',$user->id)->first();
       // $body = $request->user()->name . " Your Post is Pending For Approval By the Admin.";
        //$device_tokens = DeviceToken::where('user_id', $user->id)->pluck('value')->toArray();
       // $additional_info = [
         //   "type" => "Approval",
           // "id"  => $post->id,
       // ];
          
        //if (count($device_tokens) != 0) {
          //  $result =  sendPushNotification($body ,$device_tokens ,$additional_info);
        //}

        # Send Email Notification
        //SendEmailJob::dispatchAfterResponse(new SendEmailJob([
          //  'to' => $email->email,
           // 'title' => 'Alert | Happy tails TV',
           // 'body' => "{$request->user()->name} Your Post is Pending For Approval By the Admin.",
           // 'subject' => 'Alert | Happy tails TV'
       // ]));

       
        # Add Notification to DB
       // Notifications::create([
         //   'title'=>"Happy Tails TV",
          //  'notification'=>"{$request->user()->name} Your Post is Pending For Approval By the Admin.",
           // 'user_id'=>$user->id,
           // 'type'=>'Approval',
            //  'post_id' => $post->id,

        //]);


        return [
            'message' => 'success',
            'data' => new PostResource($post)
        ];
    }

    // V1create
    public function v1Create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'portfolio' => 'nullable|url|max:191',
            'skill' => 'required|array|min:1',
            'upwork' => 'nullable|string|max:191',
            'fiverr' => 'nullable|string|max:191',
            'linkedin' => 'nullable|string|max:191',
            'youtube' => 'nullable|string|max:191',
            'instagram' => 'nullable|string|max:191',
            'facebook' => 'nullable|string|max:191',
            'tiktok' => 'nullable|string|max:191',
            'twitter' => 'nullable|string|max:191',
            'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm,mov',
            'thumbnail' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->getMessages()], 403);
        }
        $user = $request->user();
        $userPostCount = Post::where('user_id', $user->id)->count();
        $status = 'published';
        if($userPostCount == 0){
            $status = 'under-review';
        }

        $request->merge([
            'user_id'=> $user->id,
            'status' => $status,
            'status_description' => '',
            "active" => 1,
            "is_featured" => 0,
            "is_approved_by_admin" => 1,
        ]);

        $arr = $request->only([
            'user_id',
            'title',
            'description',
            'portfolio',
            'upwork',
            'fiverr',
            'linkedin',
            'instagram',
            'facebook',
            'youtube',
            'tiktok',
            'twitter',
            'status',
            'status_description',
            'active',
            'is_featured',
            'is_approved_by_admin',
        ]);

        $arr['video'] = $this->storeVideo($request->video);
        $arr['thumbnail'] = $request->has('thumbnail') ? $request->thumbnail->store('/thumbnail') : null;
        $arr['skills'] = json_encode($request->skill);
        $post = Post::forceCreate($arr);

        return [
            'message' => 'success',
            'data' => new PostResource($post)
        ];
    }
    // end
    //update the post 
      public function update(Request $request, $post)
    {
        $post = Post::find($post);
        if (!$post)
        {
            return [
                'message' => 'error',
                'error' => 'Post does not exist.'
            ];
        }

        if ($request->user()->id != $post->user_id)
        {
            return [
                'message' => 'error',
                'error' => "You can not edit other user's post."
            ];
        }
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'portfolio' => 'nullable|url|max:191',
            'skill' => 'required|array|min:1',
            'upwork' => 'nullable|string|max:191',
            'fiverr' => 'nullable|string|max:191',
            'linkedin' => 'nullable|string|max:191',
            'youtube' => 'nullable|string|max:191',
            'instagram' => 'nullable|string|max:191',
            'facebook' => 'nullable|string|max:191',
            'tiktok' => 'nullable|string|max:191',
            'twitter' => 'nullable|string|max:191',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->getMessages()], 403);
        }

        $user = $request->user();
        $userPostCount = Post::where('user_id', $user->id)->count();
        $status = 'published';
        if($userPostCount == 0){
            $status = 'under-review';
        }

        $request->merge([
            'status' => $status,
            'status_description' => '',
        ]);

        $arr = $request->only([
            'title',
            'description',
            'portfolio',
            'upwork',
            'fiverr',
            'linkedin',
            'instagram',
            'facebook',
            'youtube',
            'tiktok',
            'twitter',
            'status',
            'status_description',
        ]);

        $arr['skills'] = $request->skill;

        Post::where('id',$post->id)->update($arr);
        
        $post = Post::where('id',$post->id)->first();

        return [
            'message' => 'success',
            'data' => new PostResource($post)
        ];
    }
    //end

    public function storeVideo($video)
    {
            $extension = $video->getClientOriginalExtension();
            $name = time().$video->getClientOriginalName();
            //\Log::info('$name: '.$name);
        // $video->move(public_path('uploads'), $name);
        // return asset('uploads/'.$name);
          //  $filePath = 'postImages/' . $name;
            $filePath = 'PostVideos/' . $name;
            $path = Storage::disk('s3')->put("PostVideos", $video);
        //     \Log::info('$path: '.$path);
        //   // return $path = Storage::disk('s3')->url($path);
        //      $filename = explode('.'.$extension, $path);
        //     \Log::info('$filename: '.$filename);
        //      $str = $filename[0];
        //     \Log::info('$str: '.$str);
             
             
        //      $filename = explode('.'."mov", $str);
        //     \Log::info('$filename: '.$filename);filepath
        //      $str = $filename[0];
        //     \Log::info('$str: '.$str);
             
        //     $str2 = substr($str, 11);
            //\Log::info('$str2: '.$str2);
            $name = str_replace('PostVideos/', '', $path);
           return "https://jobreels.s3.us-east-2.amazonaws.com/PostVideos/".$name;
           // return "https://d3sv71kjojrkuk.cloudfront.net/PostVideos/HLS/".$str2."_540.m3u8";
            
        //return $video->store('videos');
    }
     public function storethumbnail($thumbnail)
    {
        return $thumbnail->store('/thumbnail');
    }

    public function list(Request $request)
    {
        $posts = Post::where('is_approved_by_admin',1)
            ->where('user_id', $request->user()->id)
            ->where('active', 1)
            ->with('user')
            ->whereHas('user', function($q) {
                $q->where('active', 1)
                ->where('active_publisher', 1);
            })
            ->orderByDesc('created_at')
            ->get();

        return [
            'message' => 'success',
            'data' => PostResource::collection($posts)
        ];
    }


    public function listGuest(Request $request)
    {
        $posts = Post::where('is_approved_by_admin',1)
            ->where('active', 1)
            ->with('user')
            ->whereHas('user', function($q) {
                $q->where('active', 1)
                ->where('active_publisher', 1);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->get();
        $notification_counter = 0 ;
        $message_counter = 0 ;
        if($request->user_id != 0)
        {
            $user_id = $request->user_id;
            $notification_counter = \DB::table('notifications')->where(['user_id' => $user_id,'is_read' => 0 ])->count();
            $threadIds = Threads::where(function($query) use ($user_id) {
                return $query->where('from_user_id','=',$user_id)
                    ->where('d_from_user','=',0);
            })
            ->orWhere(function($query) use ($user_id) {
                return $query->where('to_user_id','=',$user_id)
                    ->where('d_to_user','=',0);
            })->pluck('id');
           $message_counter =  Messages::whereIn('thread_id',$threadIds)
            ->where('from_user_id','!=', $user_id)
            ->where('is_read', 0)
            ->where('d_from_user',0)
            ->count();
        }
        
        return [
            'message' => 'success',
            'notification_counter' => $notification_counter,
            'message_counter' => $message_counter,
            'data' => PostResource::collection($posts)
        ];
    }

    public function getPostById(Request $request)
    {
        $post = Post::find($request->id);
        return [
            'message' => 'success',
            'data' => new PostResource($post)
        ];
    }
     public function deletePostById($id)
    {
        $post = Post::where('id',$id)->update(['active'=> 0 ]);
        return [
            'message' => 'success',
           // 'data' => new PostResource($post)
        ];
    }

    public function destroy(Request $request, $post)
    {
        $post = Post::find($post);
        if (!$post)
        {
            return [
                'message' => 'error',
                'error' => 'Post does not exist.'
            ];
        }

        if ($request->user()->id != $post->user_id)
        {
            return [
                'message' => 'error',
                'error' => "You can not delete other user's post."
            ];
        }

        if (Post::where('id',$post->id)->update(['active'=> 0 ]))
        {
            return [
                'message' => 'success',
                'data' => new PostResource($post)
            ];
        }
        else
        {
            return [
                'message' => 'error',
                'error' => 'Post not deleted.'
            ];
        }
    }
}
