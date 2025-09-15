<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Aws\Exception\AwsException;
use Aws\MediaConvert\MediaConvertClient;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\{Post,User,DeviceToken};
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Storage;
use App\Models\Notifications;

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
    public function createJob($inputPath)
    {

        $mediaConvertEndpoint = config('services.mediaconvert.endpoint');
        $region = env('AWS_DEFAULT_REGION');
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');

        // Create the MediaConvert client
        $mediaConvertClient = new MediaConvertClient([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
            'endpoint' => $mediaConvertEndpoint,
        ]);







        try {
            $result = $mediaConvertClient->createJob([
                'Role' => 'arn:aws:iam::341658665798:role/service-role/MediaConvert_Default_Role',// Replace with your IAM role ARN
                'Settings' => [
                    'OutputGroups' => [
                        [
                            'Name' => 'HLS Group',
                            'OutputGroupSettings' => [
                                'Type' => 'HLS_GROUP_SETTINGS',
                                'HlsGroupSettings' => [
                                    'Destination' => 's3://jobreels/', // Change to your output bucket
                                    'SegmentLength' => 6, // Segment length for HLS chunks
                                    'MinSegmentLength' => 1,
                                    'CodecSpecification' => 'RFC_4281',
                                    'DestinationSettings' => [
                                        'S3Settings' => [
                                            'AccessControl' => [
                                                'CannedAcl' => 'PUBLIC_READ'
                                            ]
                                        ]
                                    ],
                                ],
                            ],
                            'Outputs' => [
                                [
                                    'VideoDescription' => [
                                        'Width' => 1920,
                                        'Height' => 1080,
                                        'CodecSettings' => [
                                            'Codec' => 'H_264',
                                            'H264Settings' => [
                                                'RateControlMode' => 'QVBR',
                                                'QualityTuningLevel' => 'SINGLE_PASS',
                                                'MaxBitrate' => 3000000, // 3 Mbps for good quality
                                                'GopSize' => 60,
                                                'GopSizeUnits' => 'FRAMES',
                                                'FramerateControl' => 'INITIALIZE_FROM_SOURCE',
                                                'Profile' => 'MAIN',
                                                'Level' => '4.0',
                                            ],
                                        ],
                                        'ScalingBehavior' => 'DEFAULT',
                                        'Sharpness' => 50,
                                        'AntiAlias' => 'ENABLED',
                                    ],
                                    'AudioDescriptions' => [
                                        [
                                            'AudioSelectorName' => 'Audio Selector 1',
                                            'CodecSettings' => [
                                                'Codec' => 'AAC',
                                                'AacSettings' => [
                                                    'Bitrate' => 96000,
                                                    'CodingMode' => 'CODING_MODE_2_0',
                                                    'SampleRate' => 48000,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'ContainerSettings' => [
                                        'Container' => 'M3U8',
                                    ],
                                    'NameModifier' => '_hls',
                                ],
                            ],
                        ],
                    ],
                    'Inputs' => [
                        [
                            'FileInput' => 's3://jobreels/'.$inputPath,
                            'VideoSelector' => [
                                'ColorSpace' => 'FOLLOW',
                            ],
                            'AudioSelectors' => [
                                'Audio Selector 1' => [
                                    'DefaultSelection' => 'DEFAULT',
                                ],
                            ],
                        ],
                    ],
                ],
                'Notifications' => [
                    'Progressing' => 'arn:aws:sns:us-east-2:341658665798:Media', // SNS topic ARN for progress notifications
                    'Complete' => 'arn:aws:sns:us-east-2:341658665798:Media',   // SNS topic ARN for job completion notifications
                    'Error' => 'arn:aws:sns:us-east-2:341658665798:Media',      // SNS topic ARN for error notifications
                ],
            ]);

            return response()->json(['message' => 'MediaConvert HLS job created successfully', 'job' => $result]);
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        $fullThumbNail='';
        if ($request->hasFile('thumbnail')) {

            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = 'thmb'.time() . $request->thumbnail->getClientOriginalName();
            $fullThumbNail='thumbnail/'.$name;
            $image = Image::make($request->thumbnail)->resize(480, 750, function ($constraints) {
                $constraints->aspectRatio();
            })->encode('jpg');
            Storage::put($fullThumbNail, (string)$image);
        }

        $arr['video'] = $this->storeVideo($request->video);
        $arr['thumbnail'] = $fullThumbNail;
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
        $fullThumbNail='';
        if ($request->hasFile('thumbnail')) {

            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = 'thmb'.time() . $request->thumbnail->getClientOriginalName();
            $fullThumbNail='thumbnail/'.$name;
            $image = Image::make($request->thumbnail)->resize(480, 750, function ($constraints) {
                $constraints->aspectRatio();
            })->encode('jpg');
            Storage::put($fullThumbNail, (string)$image);
        }

        $arr['video'] = $this->storeVideo($request->video);
        $arr['thumbnail'] =$fullThumbNail;
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

    public function getJob($jobId)
    {
        $mediaConvertEndpoint = config('services.mediaconvert.endpoint');
        $region = env('AWS_DEFAULT_REGION');
        $key = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');

        // Create the MediaConvert client
        $mediaConvertClient = new MediaConvertClient([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
            'endpoint' => $mediaConvertEndpoint,
        ]);

        try {
            // Retrieve job by jobId
            $result = $mediaConvertClient->getJob([
                'Id' => $jobId,
            ]);

            // Return or process the job details
//            return response()->json(['job' => $result['Job']]);

            $outputFileNames = [];
            $job = $result;

//            dd($job);

            if (isset($job['OutputGroupDetails'])) {
                foreach ($job['OutputGroupDetails'] as $outputGroup) {
                    if (isset($outputGroup['OutputDetails'])) {
                        foreach ($outputGroup['OutputDetails'] as $outputDetail) {
                            if (isset($outputDetail['OutputFilePaths'])) {
                                foreach ($outputDetail['OutputFilePaths'] as $filePath) {
                                    $outputFileNames[] = $filePath; // Store file path or name
                                }
                            }
                        }
                    }
                }
            }

            // Return output file names
            return response()->json(['outputFiles' => $outputFileNames]);
        } catch (AwsException $e) {
            // Return error response
            return response()->json(['error' => $e->getMessage()], 500);
        }

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
           $job= $this->createJob($path);
//           dd($job);
            $name = str_replace('PostVideos/', '', $path);
            $name=str_replace('.mp4', '.m3u8', $name);
           return "https://d3gzbdgdgrzuco.cloudfront.net/".$name;
//           return "https://jobreels.s3.us-east-2.amazonaws.com/PostVideos/".$name;
           // return "https://d3sv71kjojrkuk.cloudfront.net/PostVideos/HLS/".$str2."_540.m3u8";
        //return $video->store('videos');
    }
     public function storethumbnail($thumbnail)
    {
        $extension = $thumbnail->getClientOriginalExtension();
        $name = time().$thumbnail->getClientOriginalName();
        $image=Image::make($thumbnail)->resize(512,512,function ($constraints){
            $constraints->aspectRatio();
        })->encode('jpg');
        $path = Storage::disk('s3')->put('thumbnail/'.$name.'.'.$extension, (string)$image);



//        return $thumbnail->store('/thumbnail');

         return $path;


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
