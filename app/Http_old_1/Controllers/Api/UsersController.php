<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\DeviceToken;
use App\Models\Followings;
use App\Models\Notifications;
use App\Models\User;
use App\Models\Freelancer;
use App\Models\PostSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{DB, Validator, Http, Mail};
use Kutia\Larafirebase\Facades\Larafirebase;
// Constants
use App\Constants\ResponseCode;
use App\Constants\Message;
use Twilio\Rest\Client;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostSaveResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\FreelancerResource;
use App\Models\ApprovalHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class UsersController extends Controller
{
public function searchFreelancer(Request $request)
	{		
		$freelancer = Freelancer::getQuery();
		if(isset($request->title))
		{
			$freelancer = $freelancer->where('job_title','like' , '%'.$request->title.'%');
		}
		if(isset($request->description))
		{
			$freelancer = $freelancer->where('description','like' , '%'.$request->description.'%');
		}
		if(isset($request->skills_experience))
		{
			$freelancer = $freelancer->where('skills_experience','like' , '%'.$request->skills_experience.'%');
		}
		if(isset($request->availablity))
		{
			$availablity = ($request->availablity == "Any") ? ["Part time","Full time"] : [$request->availablity];
			$freelancer = $freelancer->whereIn('full_time',$availablity);
			
		}
		if(isset($request->skills))
		{
			$freelancer = $freelancer->where( 'skills_assessment' , 'like' , '%'.$request->skills.'%');
		}
		if($request->years_experience == "None")
		{
			return [
				'message' => 'success',
				'data' => [],
			];
		}
		if($request->years_experience == "Less than 1 year")
		{
			$freelancer = $freelancer->whereIn('years_experience',['Fresh',$request->years_experience]);
		}
		if($request->years_experience == "More than 1 years")
		{
			$freelancer = $freelancer->whereNotIn('years_experience',['Fresh','Less than 1 year'])->whereNotNull('years_experience');
		}
		$freelancer = $freelancer->get();
		return [
			'message' => 'success',
			'data' => FreelancerResource::collection($freelancer)
		];
	}
	public function userDataValidation(Request $request)
	{
		if($request->type == "email")
		{
			$validator = Validator::make($request->all(),[
				'email' => 'required|email|max:191|unique:users,email',
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->getMessages()], 403);
			}
		}
		else
		{
			$validator = Validator::make($request->all(),[
				'phone' => 'required|string|max:191|unique:users,phone',
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->getMessages()], 403);
			}
		}
		return [
			'message' => 'success',
			'code' => 200,
			'status'=> true
		];
	}
	public function registerHirer(Request $request)
	{
		$request->validate([
			'business_name' => 'nullable|string|max:191',
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'email' => 'required|email|max:191|unique:users,email',
			'phone' => 'required|string|max:191|unique:users,phone',
			'password' => 'required|string|confirmed|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				//Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'zip_code' => 'required|string|max:191',
			'industry' => 'required|string|max:191',
			//'employee_id' => 'nullable|string|max:191',
		]);

		$request->merge([
			'type' => 'hirer',
			'active' => true,
			'is_email_private' => $request->is_email_private??0,
			'is_phone_private' => $request->is_phone_private??0,
			'password' => \Hash::make($request->password),
			'uuid' => \Str::uuid(),
		]);

		$user = User::forceCreate(
			$request->only([
				'type',
				'business_name',
				'first_name',
				'last_name',
				'email',
				'phone',
				'password',
				'uuid',
				'state',
				'city',
				'address',
				'zip_code',
				'industry',
				//'employee_id',
				'active',
				'description',
				'is_email_private',
				'is_phone_private'
			])
		);

		$token = $user->createToken('app');

		if (isset($request->FCMToken) && $request->FCMToken) {
			DeviceToken::where('value', $request->FCMToken)->where('user_id', '!=', $user->id)->delete();

			DeviceToken::firstOrCreate(['user_id' => $user->id, 'value' => $request->FCMToken]);
		}
		$adminuser=User::where('type','admin')->first();
		Notifications::create([
            'title' => "New Hirer Request For Approval",
            'notification' => "{$user->first_name} {$user->last_name} Has Request for hirer",
            'user_id' => $adminuser->id,
            'type' => 'Adminapproval',
            'post_id' => null,
        ]);
		ApprovalHistory::create([
			'user_id'=>$user->id,
			 'type'=>'Request For Approval'
		]);
		return [
			'message' => 'success',
			'code' => 200,
			'data' => new UserResource($user),
			'token' => $token->plainTextToken
		];
	}

	public function registerHirerOtp(Request $request)
	{
		$request->validate([
			'otpType' => 'required|string|in:email,phone',
			'business_name' => 'nullable|string|max:191',
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'email' => 'required|email|max:191|unique:users,email',
			'phone' => 'required|string|max:191|unique:users,phone',
			'password' => 'required|string|confirmed|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				//Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'zip_code' => 'required|string|max:191',
			'industry' => 'required|string|max:191',
			//'employee_id' => 'nullable|string|max:191',
		]);

		$code = rand(1000, 9999);
		// $code = 1235;
		$type = $request->otpType;
		if($type == 'email')
		{
			try {
				app('view')->addNamespace('mail', resource_path('views') . '/mail');
				\Mail::send('emails.sendOtp', [
					'email' => $request->email,
					'code' => $code,
				], function ($message) use ($request) {
	            // $message->from('info@stackcru.website', 'The Alkhizra Team');
					$message->to($request->email)->subject("Verify Your Email");
				});
			}
			catch (\Exception $e) {  }
		}
		else if ($type == 'phone')
		{
			$message = 'Use the code '.$code .' to verify your Jobreels Account.';

			try 
			{
				$country_code = "+1" ;
				$phone_with_code = $country_code.$request->phone ; 

				$account_sid = getenv("TWILIO_SID");
				$auth_token = getenv("TWILIO_TOKEN");
				$twilio_number = getenv("TWILIO_FROM");

				$client = new Client($account_sid, $auth_token);
				$send = $client->messages->create($phone_with_code, [
					'from' => $twilio_number, 
					'body' => $message
				]);
			}
			catch (\Exception $e) 
			{
				return response([
					'message' => 'The given data was invalid.',
					'errors' => [
        				'phone' => [$phone_with_code. ' is not a valid phone number.']
        			]
				], 422);
				// return [
				// 	'message' => 'error',
				// 	'error' => $e->getMessage()
				// ];
				//return $response = makeResponse(ResponseCode::FAIL,$e->getMessage(),false);
			}
		}

		$time = time();
		$time = 1;

		return response([
			'message' => 'success',
			//'verify' => $code,
			'verify' => ($code*$time)+$time,
			'time' => $time,
			'code'   => 200
		], 200);
	}

	public function registerFreelancer(Request $request)
	{
		$request->validate([
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'email' => 'required|email|max:191|unique:users,email',
			'phone' => 'required|string|max:191|unique:users,phone',
			'password' => 'required|string|confirmed|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'portfolio_website' => 'nullable|url|max:191',
			// 'description' => 'required|string|max:191',
			'salary_requirements' => 'required|string|max:80',
			'full_time' => 'required|string|max:191|in:Full time,Part time,Either,Any',
			// 'hourly_rate' => 'required|string|max:191',
			// 'skills_experience' => 'required|string|max:1200',
			'skills_assessment' => 'required|array|min:1',
			'upwork' => 'nullable|string|max:191',
			'fiverr' => 'nullable|string|max:191',
			'linkedin' => 'nullable|string|max:191',
			'youtube' => 'nullable|string|max:191',
			'instagram' => 'nullable|string|max:191',
			'facebook' => 'nullable|string|max:191',
			'tiktok' => 'nullable|string|max:191',
			'twitter' => 'nullable|string|max:191',
			'photo' => 'nullable|image',
			'photo_of_govt_id' => 'nullable|image',
			'photo_of_govt_id_back' => 'nullable|image',
			'photo_with_govt_id' => 'nullable|image',
			'bills' => 'nullable|image',
			'date_of_birth' => 'nullable|string|max:191',
			'gender' => 'nullable|string|in:Male,Female,Prefer not to say',
			'years_experience' => 'nullable|string|in:Fresh,Less than 1 year,1-2 years,3+ years,1 year,2 years,3 years,4 years,5 years,More than 5 years',
			'job_title' =>  'nullable|string|max:191',
		]);

		$request->merge([
			'type' => 'freelancer',
			'active' => true,
			'is_email_private' => $request->is_email_private??0,
			'is_phone_private' => $request->is_phone_private??0,
			'password' => \Hash::make($request->password),
			'uuid' => \Str::uuid(),
		]);

		$user = User::forceCreate(
			$request->only([
				'type',
				'first_name',
				'last_name',
				'email',
				'phone',
				'password',
				'uuid',
				'state',
				'city',
				'address',
				'active',
				'is_phone_private',
				'is_email_private'
			])
		);

		$request->merge([
			'user_id' => $user->id,
			'skills_assessment' => json_encode($request->skills_assessment),
			'date_of_birth' => date('Y-m-d H:i:s', strtotime($request->date_of_birth)),
		]);

		$freelancer = Freelancer::forceCreate(
			$request->only([
				'user_id',
				'portfolio_website',
				'description',
				'salary_requirements',
				'full_time',
				// 'hourly_rate',
				'skills_experience',
				'skills_assessment',
				'upwork',
				'fiverr',
				'linkedin',
				'instagram',
				'facebook',
				'youtube',
				'tiktok',
				'twitter',
				'date_of_birth',
				'gender',
				'years_experience',
				'job_title',
			])
		);

		if ($freelancer->portfolio_website || $freelancer->upwork || $freelancer->fiverr || 
			$freelancer->linkedin || $freelancer->youtube || $freelancer->instagram || 
			$freelancer->facebook || $freelancer->tiktok || $freelancer->twitter)
		{
			$freelancer->update(['verification_level' => 'Pro Verified']);
		}
		else
		{
			$freelancer->update(['verification_level' => 'Verified']);
		}

		if ($request->has('photo')) {
			$freelancer->photo = $request->photo->store('/users');
			$freelancer->update();

			$user->profile_picture = $freelancer->photo;
			$user->update();
		}
		if ($request->has('photo_of_govt_id')) {
			$freelancer->photo_of_govt_id = $request->photo_of_govt_id->store('/users');
			$freelancer->update();
		}
		if ($request->has('photo_of_govt_id_back')) {
			$freelancer->photo_of_govt_id_back = $request->photo_of_govt_id_back->store('/users');
			$freelancer->update();
		}
		if ($request->has('photo_with_govt_id')) {
			$freelancer->photo_with_govt_id = $request->photo_with_govt_id->store('/users');
			$freelancer->update();
		}
		if ($request->has('bills')) {
			$freelancer->bills = $request->bills->store('/users');
			$freelancer->update();
		}

		$user->update(['freelancer_id' => $freelancer->id]);

		$user->freelancer->update(['verification_score' => $this->getVerificationScore($user)]);

		$token = $user->createToken('app');

		if (isset($request->FCMToken) && $request->FCMToken) {
			DeviceToken::where('value', $request->FCMToken)->where('user_id', '!=', $user->id)->delete();

			DeviceToken::firstOrCreate(['user_id' => $user->id, 'value' => $request->FCMToken]);
		}
		$adminuser=User::where('type','admin')->first();
		Notifications::create([
            'title' => "New Jobseeker Request For Approval",
            'notification' => "{$user->first_name} {$user->last_name} Has Request for JobSeeker",
            'user_id' => $adminuser->id,
            'type' => 'Adminapproval',
            'post_id' => null,
        ]);
		ApprovalHistory::create([
			'user_id'=>$user->id,
			 'type'=>'Request For Approval'
		]);
		return [
			'message' => 'success',
			'code' => 200,
			'data' => new UserResource($user),
			'token' => $token->plainTextToken
		];
	}

	public function registerFreelancerOtp(Request $request)
	{
		$request->validate([
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'email' => 'required|email|max:191|unique:users,email',
			'phone' => 'required|string|max:191|unique:users,phone',
			'password' => 'required|string|confirmed|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'portfolio_website' => 'nullable|url|max:191',
			// 'description' => 'required|string|max:191',
			'salary_requirements' => 'required|string|max:80',
			'full_time' => 'required|string|max:191|in:Full time,Part time,Either,Any',
			// 'hourly_rate' => 'required|string|max:191',
			// 'skills_experience' => 'required|string|max:1200',
			'skills_assessment' => 'required|array|min:1',
			'upwork' => 'nullable|string|max:191',
			'fiverr' => 'nullable|string|max:191',
			'linkedin' => 'nullable|string|max:191',
			'youtube' => 'nullable|string|max:191',
			'instagram' => 'nullable|string|max:191',
			'facebook' => 'nullable|string|max:191',
			'tiktok' => 'nullable|string|max:191',
			'twitter' => 'nullable|string|max:191',
			'photo' => 'nullable|image',
			'photo_of_govt_id' => 'nullable|image',
			'photo_of_govt_id_back' => 'nullable|image',
			'photo_with_govt_id' => 'nullable|image',
			'bills' => 'nullable|image',
			'date_of_birth' => 'nullable|string|max:191',
			'gender' => 'nullable|string|in:Male,Female,Prefer not to say',
			'years_experience' => 'nullable|string|in:Fresh,Less than 1 year,1-2 years,3+ years,1 year,2 years,3 years,4 years,5 years,More than 5 years',
			'job_title' =>  'nullable|string|max:191',
		]);

		$code = rand(1000, 9999);
		// $code = 1235;
		$type = $request->otpType;
		if($type == 'email')
		{
			try {
				app('view')->addNamespace('mail', resource_path('views') . '/mail');
				\Mail::send('emails.sendOtp', [
					'email' => $request->email,
					'code' => $code,
				], function ($message) use ($request) {
	            // $message->from('info@stackcru.website', 'The Alkhizra Team');
					$message->to($request->email)->subject("Verify Your Email");
				});
			}
			catch (\Exception $e) {  }
		}
		else if ($type == 'phone')
		{
			$message = 'Use the code '.$code .' to verify your Jobreels Account.';

			try 
			{
				$country_code = "+63" ;
				$phone_with_code = $country_code.$request->phone ; 
				$account_sid = getenv("TWILIO_SID");
				$auth_token = getenv("TWILIO_TOKEN");
				$twilio_number = getenv("TWILIO_FROM");

				$client = new Client($account_sid, $auth_token);
				$send = $client->messages->create($phone_with_code, [
					'from' => $twilio_number, 
					'body' => $message
				]);
			}
			catch (\Exception $e) 
			{
				return response([
					'message' => 'The given data was invalid.',
					'errors' => [
        				'phone' => [$phone_with_code. ' is not a valid phone number.']
        			]
				], 422);
				// return [
				// 	'message' => 'error',
				// 	'error' => $e->getMessage()
				// ];
				//return $response = makeResponse(ResponseCode::FAIL,$e->getMessage(),false);
			}
		}

		$time = time();
		$time = 1;

		return response([
			'message' => 'success',
			//'verify' => $code,
			'verify' => ($code*$time)+$time,
			'time' => $time,
			'code'   => 200
		], 200);
	}

	public function login(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);

		if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) || Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
			$user = Auth::user();
			if (!$user->uuid)
			{
				$user->update(['uuid' => \Str::uuid()]);
			}
			$token = $user->createToken('app');

			if (isset($request->FCMToken) && $request->FCMToken) {
				DeviceToken::where('value', $request->FCMToken)->where('user_id', '!=', $user->id)->delete();

				DeviceToken::firstOrCreate(['user_id' => $user->id, 'value' => $request->FCMToken]);
			}

			return [
				'message' => 'success',
				'data' => new UserResource($user),
				'token' => $token->plainTextToken
			];
		} else {
			return response([
				'message' => 'Invalid username or password'
			], 422);
		}
	}

	public function logout(Request $request)
	{
		$user = $request->user();

		if (isset($request->FCMToken) && $request->FCMToken) {
			DeviceToken::where('value', $request->FCMToken)->delete();
		}
	}

	public function forgetOTP(Request $request)
	{
		$request->validate([
			'phone' => 'required|string|max:191',
			// 'email' => 'required|email|exists:users,email',	
		]);
		$type = $request->type;	
		$email = $request->email ;
		$code = rand(1000, 9999);
		// $code = 1235;
		if ($type == 'phone')
		{
			$message = 'Use the code '.$code .' to reset your Password.';
			try 
			{
				$account_sid = getenv("TWILIO_SID");
				$auth_token = getenv("TWILIO_TOKEN");
				$twilio_number = getenv("TWILIO_FROM");

				$client = new Client($account_sid, $auth_token);
				$send = $client->messages->create($request->phone, [
					'from' => $twilio_number, 
					'body' => $message
				]);
			}
			catch (\Exception $e) 
			{
				return response([
					'message' => 'The given data was invalid.',
					'errors' => [
        				'phone' => [$request->phone. ' is not a valid phone number.']
        			]
				], 422);	
			}
		}
		else
		{
			try
			{
				app('view')->addNamespace('mail', resource_path('views') . '/mail');
				\Mail::send('emails.forgetotp', [
					'email' => $request->email,
					'code' => $code,
				], function ($message) use ($email) {
					// $message->from('info@stackcru.website', 'The Alkhizra Team');
					$message->to($email)->subject("OTP");
				});
			}
			catch (\Exception $e) {  }
		}
		return response([
			'message' => 'success',
			'OTPcode' => $code,
			'code'   => 200
		], 200);
	}
	public function forgetPassword(Request $request)
	{
		$request->validate([
			'phone' => 'required|string|max:191',
			// 'email' => 'required|email|exists:users,email',
			'password'          =>  'required|confirmed',
		]);

		$phone = $request->phone;
		
		$phone = substr ($request->phone, -10);
		$user =  User::where('phone','like','%'.$phone)
		->update([
			'password' => bcrypt($request->password),
		]); 
		
		$user = User::where('phone','like','%'.$phone)->first();
		return response([
			'message' => 'success',
			'user' => new UserResource($user),
			'code'   => 200
		], 200);
	}
	
	public function updateHirer(Request $request)
	{
		$request->validate([
			'business_name' => 'nullable|string|max:191',
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				//Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'zip_code' => 'required|string|max:191',
			'industry' => 'required|string|max:191',
			//'employee_id' => 'nullable|string|max:191',
			'profile_picture' => 'nullable|image'
		]);

		$user = $request->user();

		$arr = [];

		if ($request->has('business_name'))
			$arr['business_name'] = $request->business_name == 'null' ? null : $request->business_name;
		if ($request->has('first_name'))
			$arr['first_name'] = $request->first_name == 'null' ? null : $request->first_name;
		if ($request->has('last_name'))
			$arr['last_name'] = $request->last_name == 'null' ? null : $request->last_name;
		if ($request->has('state'))
			$arr['state'] = $request->state == 'null' ? null : $request->state;
		if ($request->has('city'))
			$arr['city'] = $request->city == 'null' ? null : $request->city;
		if ($request->has('address'))
			$arr['address'] = $request->address == 'null' ? null : $request->address;
		if ($request->has('zip_code'))
			$arr['zip_code'] = $request->zip_code == 'null' ? null : $request->zip_code;
		if ($request->has('industry'))
			$arr['industry'] = $request->industry == 'null' ? null : $request->industry;
		if ($request->has('industry'))
			$arr['description'] = $request->description == 'null' ? null : $request->description;
		//if ($request->has('employee_id'))
			//$arr['employee_id'] = $request->employee_id == 'null' ? null : $request->employee_id;

		$user->update($arr);

		if ($request->has('profile_picture')) {
			$user->profile_picture = $request->profile_picture->store('/users');
			$user->update();
		}

		return [
			'message' => 'success',
			'data' => new UserResource($user)
		];
	}
	
	public function updateFreelancer1(Request $request)
	{
		$request->validate([
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'state' => 'required|string|max:191|exists:states,state',
			'city' => [
				'required', 'string', 'max:191',
				Rule::exists('states', 'city')->where('state', $request->state),
			],
			'address' => 'required|string',
			'portfolio_website' => 'nullable|url|max:191',
			'description' => 'required|string|max:191',
			'salary_requirements' => 'required|string|max:80',
			'full_time' => 'required|string|max:191|in:Full time,Part time,Either,Any',
			// 'hourly_rate' => 'required|string|max:191',
			'skills_experience' => 'required|string|max:1200',
			'skills_assessment' => 'required|array|min:1',
			'upwork' => 'nullable|string|max:191',
			'fiverr' => 'nullable|string|max:191',
			'linkedin' => 'nullable|string|max:191',
			'youtube' => 'nullable|string|max:191',
			'instagram' => 'nullable|string|max:191',
			'facebook' => 'nullable|string|max:191',
			'tiktok' => 'nullable|string|max:191',
			'twitter' => 'nullable|string|max:191',
			'profile_picture' => 'nullable|image',
			'date_of_birth' => 'nullable|string|max:191',
			'gender' => 'nullable|string|in:Male,Female',
			'years_experience' => 'nullable|string|in:Fresh,Less than 1 year,1-2 years,3+ years,1 year,2 years,3 years,4 years,5 years,More than 5 years',
			'job_title' =>  'nullable|string|max:191',
			'photo_of_govt_id' => 'nullable|image',
			'photo_of_govt_id_back' => 'nullable|image',
			'photo_with_govt_id' => 'nullable|image',
			'bills' => 'nullable|image',
		]);

		$user = $request->user();

		$arr = [];

		if ($request->has('first_name'))
			$arr['first_name'] = $request->first_name == 'null' ? null : $request->first_name;
		if ($request->has('last_name'))
			$arr['last_name'] = $request->last_name == 'null' ? null : $request->last_name;
		if ($request->has('state'))
			$arr['state'] = $request->state == 'null' ? null : $request->state;
		if ($request->has('city'))
			$arr['city'] = $request->city == 'null' ? null : $request->city;
		if ($request->has('address'))
			$arr['address'] = $request->address == 'null' ? null : $request->address;
		
		$user->update($arr);

		$arr = [];

		if ($request->has('portfolio_website'))
			$arr['portfolio_website'] = $request->portfolio_website == 'null' ? null : $request->portfolio_website;
		if ($request->has('description'))
			$arr['description'] = $request->description == 'null' ? null : $request->description;
		if ($request->has('salary_requirements'))
			$arr['salary_requirements'] = $request->salary_requirements == 'null' ? null : $request->salary_requirements;
		if ($request->has('full_time'))
			$arr['full_time'] = $request->full_time == 'null' ? null : $request->full_time;
		// if ($request->has('hourly_rate'))
			// $arr['hourly_rate'] = $request->hourly_rate == 'null' ? null : $request->hourly_rate;
		if ($request->has('skills_experience'))
			$arr['skills_experience'] = $request->skills_experience == 'null' ? null : $request->skills_experience;
		if ($request->has('skills_assessment'))
			$arr['skills_assessment'] = $request->skills_assessment == 'null' ? null : json_encode($request->skills_assessment);
		if ($request->has('upwork'))
			$arr['upwork'] = $request->upwork == 'null' ? null : $request->upwork;
		if ($request->has('fiverr'))
			$arr['fiverr'] = $request->fiverr == 'null' ? null : $request->fiverr;
		if ($request->has('linkedin'))
			$arr['linkedin'] = $request->linkedin == 'null' ? null : $request->linkedin;
		if ($request->has('youtube'))
			$arr['youtube'] = $request->youtube == 'null' ? null : $request->youtube;
		if ($request->has('instagram'))
			$arr['instagram'] = $request->instagram == 'null' ? null : $request->instagram;
		if ($request->has('facebook'))
			$arr['facebook'] = $request->facebook == 'null' ? null : $request->facebook;
		if ($request->has('tiktok'))
			$arr['tiktok'] = $request->tiktok == 'null' ? null : $request->tiktok;
		if ($request->has('twitter'))
			$arr['twitter'] = $request->twitter == 'null' ? null : $request->twitter;
		if ($request->has('date_of_birth'))
			$arr['date_of_birth'] = $request->date_of_birth == 'null' ? null : date('Y-m-d H:i:s', strtotime($request->date_of_birth));
		if ($request->has('gender'))
			$arr['gender'] = $request->gender == 'null' ? null : $request->gender;
		if ($request->has('years_experience'))
			$arr['years_experience'] = $request->years_experience == 'null' ? null : $request->years_experience;
		if ($request->has('job_title'))
			$arr['job_title'] = $request->job_title == 'null' ? null : $request->job_title;

		if ($request->has('photo_of_govt_id')) {
			$arr['photo_of_govt_id'] = $request->photo_of_govt_id->store('/users');
		}
		if ($request->has('photo_of_govt_id_back')) {
			$arr['photo_of_govt_id_back'] = $request->photo_of_govt_id_back->store('/users');
		}
		if ($request->has('photo_with_govt_id')) {
			$arr['photo_with_govt_id'] = $request->photo_with_govt_id->store('/users');
		}
		if ($request->has('bills')) {
			$arr['bills'] = $request->bills->store('/users');
		}
		
		$user->freelancer->update($arr);

		if ($user->freelancer->portfolio_website || $user->freelancer->upwork || $user->freelancer->fiverr || 
			$user->freelancer->linkedin || $user->freelancer->youtube || $user->freelancer->instagram || 
			$user->freelancer->facebook || $user->freelancer->tiktok || $user->freelancer->twitter)
		{
			$user->freelancer->update(['verification_level' => 'Pro Verified']);
		}
		else
		{
			$user->freelancer->update(['verification_level' => 'Verified']);
		}

		if ($request->has('profile_picture')) {
			$user->profile_picture = $request->profile_picture->store('/users');
			$user->update();
		}

		$user->freelancer->update(['verification_score' => $this->getVerificationScore($user)]);

		return [
			'message' => 'success',
			'data' => new UserResource($user)
		];
	}
	public function updateFreelancer(Request $request)
	{
		$validator = Validator::make($request->all(),[
				'first_name' => 'required|string|max:191',
				'last_name' => 'required|string|max:191',
				'state' => 'required|string|max:191|exists:states,state',
				'city' => [
					'required', 'string', 'max:191',
					Rule::exists('states', 'city')->where('state', $request->state),
				],
				'address' => 'required|string',
				//'portfolio_website' => 'nullable|url|max:191',
				// 'description' => 'required|string|max:191',
				'salary_requirements' => 'required|string|max:80',
				'full_time' => 'required|string|max:191|in:Full time,Part time,Either,Any',
				//'hourly_rate' => 'required|string|max:191',
				// 'skills_experience' => 'required|string|max:1200',
				'skills_assessment' => 'required|array|min:1',
				'upwork' => 'nullable|string|max:191',
				'fiverr' => 'nullable|string|max:191',
				'linkedin' => 'nullable|string|max:191',
				'youtube' => 'nullable|string|max:191',
				'instagram' => 'nullable|string|max:191',
				'facebook' => 'nullable|string|max:191',
				'tiktok' => 'nullable|string|max:191',
				'twitter' => 'nullable|string|max:191',
				'profile_picture' => 'nullable|image',
				'date_of_birth' => 'nullable|string|max:191',
				'gender' => 'nullable|string|in:Male,Female,Prefer not to say',
				'years_experience' => 'nullable|string|in:Fresh,Less than 1 year,1-2 years,3+ years,1 year,2 years,3 years,4 years,5 years,More than 5 years',
				'job_title' =>  'nullable|string|max:191',
				'photo_of_govt_id' => 'nullable|image',
				'photo_of_govt_id_back' => 'nullable|image',
				'photo_with_govt_id' => 'nullable|image',
				'bills' => 'nullable|image',
			]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->getMessages()], 403);
        }

		// $request->validate([
		// 	'first_name' => 'required|string|max:191',
		// 	'last_name' => 'required|string|max:191',
		// 	'state' => 'required|string|max:191|exists:states,state',
		// 	'city' => [
		// 		'required', 'string', 'max:191',
		// 		Rule::exists('states', 'city')->where('state', $request->state),
		// 	],
		// 	'address' => 'required|string',
		// 	'portfolio_website' => 'nullable|url|max:191',
		// 	'description' => 'required|string|max:191',
		// 	//'salary_requirements' => 'required|string|max:80',
		// 	//'full_time' => 'required|string|max:191|in:Full time,Part time',
		// 	//'hourly_rate' => 'required|string|max:191',
		// 	//'skills_experience' => 'required|string|max:1200',
		// 	//'skills_assessment' => 'required|array|min:1',
		// 	'upwork' => 'nullable|string|max:191',
		// 	'fiverr' => 'nullable|string|max:191',
		// 	'linkedin' => 'nullable|string|max:191',
		// 	'youtube' => 'nullable|string|max:191',
		// 	'instagram' => 'nullable|string|max:191',
		// 	'facebook' => 'nullable|string|max:191',
		// 	'tiktok' => 'nullable|string|max:191',
		// 	'twitter' => 'nullable|string|max:191',
		// 	'profile_picture' => 'nullable|image',
		// 	'date_of_birth' => 'nullable|string|max:191',
		// 	'gender' => 'nullable|string|in:Male,Female',
		// 	'years_experience' => 'nullable|string|in:Fresh,Less than 1 year,1 year,2 years,3 years,4 years,5 years,More than 5 years',
		// 	'job_title' =>  'nullable|string|max:191',
		// 	'photo_of_govt_id' => 'nullable|image',
		// 	'photo_of_govt_id_back' => 'nullable|image',
		// 	'photo_with_govt_id' => 'nullable|image',
		// 	'bills' => 'nullable|image',
		// ]);
		$user = $request->user();
		$totalFields = 24;
		$filledFields = 0;
		$arr = [];

		if (self::Issetcustom($request->first_name))
		{
			$arr['first_name'] = $request->first_name ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->profile_picture))
		{
			$arr['profile_picture'] = $request->profile_picture->store('/users');
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->last_name))
		{
			$arr['last_name'] = $request->last_name ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->state))
		{
			$arr['state'] = $request->state ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->city))
		{
			$arr['city'] = $request->city ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->address))
		{
			$arr['address'] = $request->address ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->profile_picture))
		{
			$arr['profile_picture'] = $request->profile_picture->store('/users');
			$filledFields++ ; 
		}
		$arr['is_phone_private'] = $request->is_phone_private;
		$arr['is_email_private'] = $request->is_email_private;
		$user->update($arr);
		$arr = [];
		$verification_level = "Verified" ; 

		if (self::Issetcustom($request->portfolio_website))
		{
			$arr['portfolio_website'] = $request->portfolio_website ;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->description))
		{
			$arr['description'] = $request->description ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->salary_requirements))
		{
			$arr['salary_requirements'] = $request->salary_requirements ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->full_time))
		{
			$arr['full_time'] = $request->full_time ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->hourly_rate))
		{
			$arr['hourly_rate'] = $request->hourly_rate == 'null' ? null : $request->hourly_rate;
		}
		if (self::Issetcustom($request->skills_experience))
		{
			$arr['skills_experience'] = $request->skills_experience == 'null' ? null : $request->skills_experience;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->skills_assessment))
		{
			$arr['skills_assessment'] = $request->skills_assessment == 'null' ? null : json_encode($request->skills_assessment);
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->upwork))
		{
			$arr['upwork'] = $request->upwork == 'null' ? null : $request->upwork;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->fiverr))
		{
			$arr['fiverr'] = $request->fiverr == 'null' ? null : $request->fiverr;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->linkedin))
		{
			$arr['linkedin'] = $request->linkedin == 'null' ? null : $request->linkedin;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->youtube))
		{
			$arr['youtube'] = $request->youtube == 'null' ? null : $request->youtube;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->instagram))
		{
			$arr['instagram'] = $request->instagram == 'null' ? null : $request->instagram;
			$verification_level = "Pro Verified" ;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->facebook))
		{
			$arr['facebook'] = $request->facebook == 'null' ? null : $request->facebook;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->tiktok))
		{
			$arr['tiktok'] = $request->tiktok == 'null' ? null : $request->tiktok;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->twitter))
		{
			$arr['twitter'] = $request->twitter == 'null' ? null : $request->twitter;
			$verification_level = "Pro Verified" ; 
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->date_of_birth))
		{
			$arr['date_of_birth'] = $request->date_of_birth == 'null' ? null : date('Y-m-d H:i:s', strtotime($request->date_of_birth));
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->gender))
		{
			$arr['gender'] = $request->gender == 'null' ? null : $request->gender;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->years_experience))
		{
			$arr['years_experience'] = $request->years_experience == 'null' ? null : $request->years_experience;
			$filledFields++ ; 
		}
		if (self::Issetcustom($request->job_title))
		{
			$arr['job_title'] = $request->job_title == 'null' ? null : $request->job_title;
			$filledFields++ ; 

		}if (self::Issetcustom($request->photo_of_govt_id))
		{
			$arr['photo_of_govt_id'] = $request->photo_of_govt_id->store('/users');
		}
		if (self::Issetcustom($request->photo_of_govt_id_back))
		{
			$arr['photo_of_govt_id_back'] = $request->photo_of_govt_id_back->store('/users');
		}
		if (self::Issetcustom($request->photo_with_govt_id))
		{
			$arr['photo_with_govt_id'] = $request->photo_with_govt_id->store('/users');
		}
		if (self::Issetcustom($request->bills))
		{
			$arr['bills'] = $request->bills->store('/users');
		}
		$arr['verification_level'] = $verification_level ; 

		$verification_score = ceil(($filledFields / $totalFields) * 100);
        if ($verification_score < 0) $verification_score = 0;
        if ($verification_score > 100) $verification_score = 100;
		$arr['verification_score'] = $verification_score ;
		$user->freelancer->update($arr);
	
		return [
			'message' => 'success',
			'data' => new UserResource($user)
		];
	}
	public function updateFreelancerProfileImage(Request $request)
	{
			$validator = Validator::make($request->all(),[
					'profile_picture' => 'required|image',
				]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()->getMessages()], 403);
			}
			$user = $request->user();
			$arr = [];
	
			if (self::Issetcustom($request->profile_picture))
			{
				$arr['profile_picture'] = $request->profile_picture->store('/users');
			}
			$user->update($arr);
			return [
				'message' => 'success',
				'data' => new UserResource($user)
			];
			
	}
	public function Issetcustom($value)
	{
		if ($value != null && $value != "NULL" && $value != "null") {
			return true;
		}
		return false;
	}
	public function deleteAccount(Request $request)
	{
		$user = $request->user();

		DeviceToken::where('user_id', $user->id)->delete();

		//$user->update(['deleted_at' => date('Y-m-d H:i:s')]);
		$user->update(['email' => md5(mt_rand(0, 9999).time()), 'phone' => md5(mt_rand(0, 9999).time())]);

		Post::where('user_id', $user->id)->delete();
		
		$subscription = auth('api')->user()->subscriptions()->active()->latest()->first();
		if($subscription)
			$subscription->cancel();

		return [
			'message' => 'success',
			'code' => 200,
		];
	}

	public function deleteAccountOtp(Request $request)
	{
		$user = $request->user();

		$request->validate([
			'otpType' => 'required|string|in:email,phone',
		]);

		$code = rand(1000, 9999);
		// $code = 1235;
		$type = $request->otpType;
		if($type == 'email')
		{
			try {
				app('view')->addNamespace('mail', resource_path('views') . '/mail');
				\Mail::send('emails.deleteAccount', [
					'email' => $user->email,
					'code' => $code,
				], function ($message) use ($user) {
	            // $message->from('info@stackcru.website', 'The Alkhizra Team');
					$message->to($user->email)->subject("Account Deletion");
				});
			}
			catch (\Exception $e) {
			  }
		}
		else if ($type == 'phone')
		{
			$message = 'Use the code '.$code .' to verify your JobReels account deletion request.';

			try 
			{
				$country_code = ($user->type == "hirer") ? "+1" : "+63" ;
				$phone = substr ($user->phone, -10); 
				$phone_with_code = $country_code.$phone ; 
				$account_sid = getenv("TWILIO_SID");
				$auth_token = getenv("TWILIO_TOKEN");
				$twilio_number = getenv("TWILIO_FROM");

				$client = new Client($account_sid, $auth_token);
				$send = $client->messages->create($phone_with_code, [
					'from' => $twilio_number, 
					'body' => $message
				]);
			}
			catch (\Exception $e) 
			{
				return response([
					'message' => 'The given data was invalid.',
					'errors' => [
        				'phone' => [$phone_with_code. ' is not a valid phone number.']
        			]
				], 422);
			}
		}

		$time = time();
		$time = 1;

		return response([
			'message' => 'success',
			//'verify' => $code,
			'verify' => ($code*$time)+$time,
			'time' => $time,
			'code'   => 200
		], 200);
	}

	private function getVerificationScore($user)
	{
		$totalFields = 27;
		$filledFields = 0;

		if ($user->profile_picture && $user->profile_picture != 'default.png') $filledFields++;
        if ($user->first_name) $filledFields++;
        if ($user->last_name) $filledFields++;
        if ($user->email) $filledFields++;
        if ($user->phone) $filledFields++;
        if ($user->freelancer->gender) $filledFields++;
        if ($user->freelancer->date_of_birth) $filledFields++;
        if ($user->state) $filledFields++;
        if ($user->city) $filledFields++;
        if ($user->address) $filledFields++;
        if ($user->freelancer->portfolio_website) $filledFields++;
        if ($user->freelancer->description) $filledFields++;
        if ($user->freelancer->salary_requirements) $filledFields++;
        if ($user->freelancer->full_time) $filledFields++;
        // if ($user->freelancer->hourly_rate) $filledFields++;
        if ($user->freelancer->years_experience) $filledFields++;
        if ($user->freelancer->job_title) $filledFields++;
        if ($user->freelancer->skills_experience) $filledFields++;
        if ($user->freelancer->skills_assessment && $user->freelancer->skills_assessment != '[]') $filledFields++;
        if ($user->freelancer->upwork) $filledFields++;
        if ($user->freelancer->fiverr) $filledFields++;
        if ($user->freelancer->linkedin) $filledFields++;
        if ($user->freelancer->youtube) $filledFields++;
        if ($user->freelancer->instagram) $filledFields++;
        if ($user->freelancer->facebook) $filledFields++;
        if ($user->freelancer->tiktok) $filledFields++;
        if ($user->freelancer->twitter) $filledFields++;

        $verification_score = ceil(($filledFields / $totalFields) * 100);
        if ($verification_score < 0) $verification_score = 0;
        if ($verification_score > 100) $verification_score = 100;

        return $verification_score;
	}

	public function follow(Request $request, $user)
	{
		$user = User::find($user);
		if (!$user)
		{
			return [
				'message' => 'error',
				'error' => 'User does not exist.'
			];
		}

		if ($request->user()->id == $user->id) {
			return [
				'message' => 'error',
				'error' => "You can not follow yourself",
			];
		}

		$is_followed = Followings::where('user_id', $user->id)->where('follower_id', $request->user()->id)->exists();
		if ($is_followed) {
			return [
				'message' => 'already',
				'user_followed' => true,
				'total_followers' => count($user->followers),
			];
		}

		$body = $request->user()->name." followed you";
		$device_tokens = DeviceToken::where('user_id', $user->id)->pluck('value')->toArray();
		$additional_info = [
			"type" => "Follow",
			"id"  => $request->user()->id,
		];
		if (count($device_tokens) != 0) {
			sendPushNotification($body, $device_tokens, $additional_info);
		}

        # Add Notification to DB
		Notifications::create([
			'title' => "Happy Tails TV",
			'notification' => "{$request->user()->name} followed you",
			'user_id' => $user->id,
			'type' => 'Follow',
			'post_id' =>  $request->user()->id,
		]);
        # Send Email Notification
        //if ($user) {
            // SendEmailJob
		SendEmailJob::dispatchAfterResponse(new SendEmailJob([
			'to' => $user->email,
			'title' => 'Alert | Happy tails TV',
			'body' => "{$request->user()->name} followed you;",
			'subject' => 'Alert | Happy tails TV'
		]));
        //}

        # Add follow
		$follow = Followings::create([
			'user_id' => $user->id,
			'follower_id' => $request->user()->id
		]);

		return [
			'message' => 'success',
			'user_followed' => true,
			'total_followers' => count($user->followers),
		];
	}

	public function unfollow(Request $request, $user)
	{
		$user = User::find($user);
		if (!$user)
		{
			return [
				'message' => 'error',
				'error' => 'User does not exist.'
			];
		}

		$is_followed = Followings::where('user_id', $user->id)->where('follower_id', $request->user()->id)->first();
		if (!$is_followed) {
			return [
				'message' => 'already',
				'user_followed' => false,
				'total_followers' => count($user->followers),
			];
		}

		$is_followed->delete();

		return [
			'message' => 'success',
			'user_followed' => false,
			'total_followers' => count($user->followers),
		];
	}


	public function notifications(Request $request)
	{
		$notifications = DB::table('notifications')->select(
			'notifications.id',
			'notifications.type',
			'notifications.title',
			'notifications.notification',
			'notifications.user_id',
			'notifications.post_id',        
			DB::raw('DATE(notifications.created_at) AS date'),
			DB::raw("TIME_FORMAT(notifications.created_at, '%H:%i') AS time")
		)
		->where('user_id',$request->user()->id)
		->orderby('created_at','desc')->get();
   DB::table('notifications')
		->where(['user_id' => $request->user()->id,'is_read' => 0])
		->update(['is_read' => 1 ]);
		return response()->json(['message' => 'success', 'data' => $notifications]);
	}
	
	public function self(Request $request)
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

		$savedPosts = $this->userSavedPosts($request);
		$likedPosts = $this->userLikedPosts($request);

		return [
			'message' => 'success',
			'data' => new UserResource($request->user()),
			'posts' => PostResource::collection($posts),
			'saved_posts' => PostSaveResource::collection($savedPosts),
			'liked_posts' => PostResource::collection($likedPosts),
		];
	}

	public function userSavedPosts(Request $request)
	{
	    return PostSave::where('user_id', $request->user()->id)
	    ->with(['post.user', 'post'])
		->whereHas('post', function($q) {
		    $q->where('is_approved_by_admin',1)
			->where('active', 1);
		})
		->whereHas('post.user', function($q) {
			$q->where('active', 1)
			->where('active_publisher', 1);
		})
		->orderByDesc('created_at')
	    ->get();
	}

	public function savedPosts(Request $request)
	{
		$posts = $this->userSavedPosts($request);

		return [
			'message' => 'success',
			'data' => PostResource::collection($posts)
		];
	}

	public function userLikedPosts(Request $request)
	{
		return Post::where('is_approved_by_admin',1)
		->where('active', 1)
		->with(['user', 'likes'])
		->whereHas('user', function($q) {
			$q->where('active', 1)
			->where('active_publisher', 1);
		})
		->whereHas('likes', function($q) use($request) {
			$q->where('user_id', $request->user()->id);
		})
		->orderByDesc('created_at')
		->get();
	}

	public function likedPosts(Request $request)
	{
		$posts = $this->userLikedPosts($request);

		return [
			'message' => 'success',
			'data' => PostResource::collection($posts)
		];
	}

	public function otherProfile(Request $request, $user)
	{
		$user = User::find($user);
		if (!$user)
		{
			return [
				'message' => 'error',
				'error' => 'User does not exist.'
			];
		}

		$posts = Post::where('is_approved_by_admin',1)
		->where('user_id', $user->id)
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
			'data' => new UserResource($user),
			'posts' => PostResource::collection($posts)
		];
	}

	function send_chat_push(Request $request, $user)
	{
		$user = User::find($user);
		if (!$user)
		{
			return [
				'message' => 'error',
				'error' => 'User does not exist.'
			];
		}

		if ($request->user()->id == $user->id) {
			return [
				'message' => 'error',
				'error' => "You can not send message to yourself",
			];
		}

		$body = $request->message;
		$device_tokens = DeviceToken::where('user_id', $user->id)->pluck('value')->toArray();
		$additional_info = [
			"type" => "Chat",
			"id"  => $request->user()->id,
		];
		if (count($device_tokens) != 0) {
			sendPushNotification($body, $device_tokens, $additional_info, $request->user()->first_name.' '.$request->user()->last_name.' sent you a message');
		}
	}
 public function counter(Request $request)
	{
		$notification_count = DB::table('notifications')->where(['user_id' => $request->user()->id,'is_read' => 0 ])->count();
		$result = [
			'notification_count' => $notification_count,
		];
		return response()->json(['message' => 'success', 'data' => $result]);
	}
}
