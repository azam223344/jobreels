<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Carbon
use Illuminate\Support\Carbon;

// JWT 
use JWTAuth;

// Models 
use App\Models\{ Threads, Messages, User};

// Constants
use App\Constants\ResponseCode;
use App\Constants\Message;

// Custom Paginator
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ThreadsController extends Controller
{
    public function addThread(Request $request)
    {
        $user =  auth()->user();
        $validator = validateData($request,'ADD_THREAD');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),null,$validator['errors']);
        \DB::beginTransaction();
        try 
        {
            $from_user_id      = $request->from_user_id;
            $to_user_id        = $request->to_user_id;
            if($from_user_id == $to_user_id)
                return makeResponse(ResponseCode::FAIL,Message::INVALID_INPUT_VALUES,null);

            $modelThread = Threads::where(function($query) use ($from_user_id,$to_user_id) {
                                return $query->where('from_user_id','=',$from_user_id)
                                    ->where('to_user_id','=',$to_user_id);
                            })
                            ->orWhere(function($query) use ($from_user_id,$to_user_id) {
                                return $query->where('from_user_id','=',$to_user_id)
                                    ->where('to_user_id','=',$from_user_id);
                            })
                            ->with('FromUser')
                            ->with('ToUser')
                            ->with('Message')
                            ->first();

            if(empty($modelThread))
            {
                $modelThread = Threads::create([ 
                                        "from_user_id"      => $from_user_id,
                                        "to_user_id"        => $to_user_id,
                                        "d_from_user"       => 1,
                                        "d_to_user"         => 1,
                                    ]);

                $modelThread = Threads::where([
                                            "from_user_id"      => $from_user_id,
                                            "to_user_id"        => $to_user_id
                                        ])
                                        ->with('FromUser')
                                        ->with('ToUser')
                                        ->with('Message')
                                        ->first();
            }
            \DB::commit();
            $response = makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL,$modelThread,null);
        }
        catch (\Exception $e) 
        {
            \DB::rollBack();
            $response = makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
        return $response;
    }
    public function clearThread(Request $request)
    {
        $user =  auth()->user();

        $response = getDefaultResponse();
        $validator = validateData($request,'CLEAR_THREAD');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),null,$validator['errors']);

        \DB::beginTransaction();
        try 
        {
            $thread_id = $request->thread_id;

            $thread = Threads::where('id',$thread_id)->firstOrFail();

            if($thread->from_user_id == $user->id)
                $messageUpdateObj = ["d_from_user"=>1];
            else
                $messageUpdateObj = ["d_to_user"=>1];

            $message = Messages::where('thread_id',$thread_id)
                                ->update($messageUpdateObj);
            
            \DB::commit();
            $response = makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL);
        }
        catch (\Exception $e) 
        {
            \DB::rollBack();
            $response = makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
        return $response;
    }


    public function deleteThread(Request $request)
    {
        $user =  auth()->user();
        $response = getDefaultResponse();
        $validator = validateData($request,'DELETE_THREAD');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),null,$validator['errors']);

        \DB::beginTransaction();
        try 
        {
            $thread_id = $request->thread_id;

            $thread = Threads::where('id',$thread_id)->first();
            if($thread->from_user_id == $user->id)
            {
                $threadUpdateObj = ["d_from_user"=>1];
                $messageUpdateObj = ["d_from_user"=>1, "is_read" =>1];
            }
            else
            {
                $threadUpdateObj = ["d_to_user"=>1];
                $messageUpdateObj = ["d_to_user"=>1, "is_read" =>1];
            }

            $thread = Threads::where('id',$thread_id)
                            ->update($threadUpdateObj);
            $message = Messages::where('thread_id',$thread_id)
                            ->update($messageUpdateObj);

            \DB::commit();

            $response = makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL);
        }
        catch (\Exception $e) 
        {
            \DB::rollBack();
            $response = makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
        return $response;
    }

    public function paginate($items, $perPage = 1, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
	 public function list(Request $request)
    {
        $user =  auth()->user();
        $response = getDefaultResponse();
        try 
        {	
			$user_id = $user->id;
            $modelThreads = Threads::has('Message')
                                ->where(function($query) use ($user_id) {
                                    return $query->where('from_user_id','=',$user_id)
                                        ->where('d_from_user','=',0);
                                })
                                ->orWhere(function($query) use ($user_id) {
                                        return $query->where('to_user_id','=',$user_id)
                                        ->where('d_to_user','=',0);
                                })
                                ->with('FromUser')
                                ->with('ToUser')
                                ->get();

            foreach ($modelThreads as $value) 
            {
                $value['undreadCount'] = Messages::where('thread_id',$value->id)
                                            ->where('from_user_id','!=',$user_id)
                                            ->where('is_read',0)
                                            ->where('d_from_user',0)
                                            ->count();

                if($value->from_user_id == $user_id)
                {
                    $message = Messages::where('thread_id',$value->id)
                                            // ->where('d_from_user',0)
                                            ->orderBy('id','desc')
                                            ->first();
                    $last_message = Messages::where('thread_id',$value->id)
                                            ->where('d_from_user',0)
                                            ->orderBy('id','desc')
                                            ->first();
                    $value['message'] = $last_message;
                    $lastDate = $message?Carbon::parse($message['created_at'])->format('Y-m-d H:i:s'):Carbon::parse($value['updated_at'])->format('Y-m-d H:i:s');
                    $value['lastDate'] = $lastDate;
                }
                else
                {
                    $message = Messages::where('thread_id',$value->id)
                                            ->orderBy('id','desc')
                                            ->first();
                    $last_message = Messages::where('thread_id',$value->id)
                                            ->where('d_to_user',0)
                                            ->orderBy('id','desc')
                                            ->first();
                    $value['message'] = $last_message ;
                    $lastDate = $message?Carbon::parse($message['created_at'])->format('Y-m-d H:i:s'):Carbon::parse($value['updated_at'])->format('Y-m-d H:i:s');
                    $value['lastDate'] = $lastDate;
                }
            }
            $modelThreads = $modelThreads->sortByDesc('lastDate')->values()->all();
			$result = [
			'threads' => $modelThreads,
			];
            
            return makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL,$result,null);
        }
        catch (\Exception $e) 
        {
            \DB::rollBack();
            $response = makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
        return $response;
    }
}
