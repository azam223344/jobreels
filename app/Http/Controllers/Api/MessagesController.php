<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\{Threads, Messages};

// Constants
use App\Constants\{ ResponseCode, Message };

class MessagesController extends Controller
{
    public function markMessagesRead(Request $request)
    {
        $validator = validateData($request,'MARK_MESSAGES_READ');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),null,$validator['errors']);

        DB::beginTransaction();
        try
        {
            Messages::where('thread_id', $request->thread_id)
                    ->where('from_user_id','<>', auth()->user()->id)
//                    ->where('is_read', 0)
                    ->update([
                                "is_read"   => 1
                            ]);
            DB::commit();
            return makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
    }
    public function deleteMsg(Request $request)
    {
        $validator = validateData($request,'DELETE_MESSAGE');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),null,$validator['errors']);

        DB::beginTransaction();
        try
        {
            $message_number = $request->message_number;
            $user_id = auth()->user()->id;

            $message = Messages::where('message_number',$message_number)->first();
            $thread = Threads::where('id',$message->thread_id)->first();

            $message = ($thread->from_user_id == $user_id) ? $message->update([
                                                                "d_from_user"   => 1
                                                        ]) : $message->update([
                                                                "d_to_user"     => 1
                                                        ]);
            DB::commit();
            return makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
    }

    public function notificationCounter(Request $request){
       $counter= Messages::whereHas('Thread',function($q) use ($request){
               $q->where('to_user_id','=', auth()->user()->id)->orWhere('from_user_id','=', auth()->user()->id);
           })->where('from_user_id','<>', auth()->user()->id)
                    ->where('is_read', 0)->count();
        $notification_count = DB::table('notifications')->where(['user_id' => $request->user()->id,'is_read' => 0 ])->count();
        return makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL,['m_message_count'=>$counter,'notification_count'=>$notification_count]);
    }
	public function listMsg(Request $request)
    {
        $validator = validateData($request,'LIST_MESSAGE');
        if ($validator['status'])
            return makeResponse(ResponseCode::FAIL,$validator['errors']->first(),false,$validator['errors']);

        try
        {
            $thread_id 			 = $request->thread_id;
            $user_id    		 = auth()->user()->id;
			$deletedMessageList  = [];
			$date			     = $request->date;
			if(isset($date))
			{
            	$thread   = Threads::where('id',$thread_id)->first();
            	$messages =  Messages::where('thread_id',$thread_id);
            	$messages = ($thread->from_user_id == $user_id) ? $messages->where('d_from_user',0) : $messages->where('d_to_user',0);
            	$messages = $messages->where('id','>',$request->msgId)
								->with('parentMessage')
                                ->orderBy('id','asc')
                                ->get();
				$deletedMessageList =  Messages::where('thread_id',$thread_id);

				$deletedMessageList = ($thread->from_user_id == $user_id) ? $deletedMessageList->where('d_from_user',1) : $deletedMessageList->where('d_to_user',1);
            	$deletedMessageList = $deletedMessageList->whereDate('updated_at','>=',\Carbon\Carbon::parse($date))->pluck('message_number');

			}
			else
			{
            	$thread   = Threads::where('id',$thread_id)->first();
            	$messages = Messages::where('thread_id',$thread_id);
            	$messages = ($thread->from_user_id == $user_id) ? $messages->where('d_from_user',0) : $messages->where('d_to_user',0);
            	$messages = $messages
                                ->with('parentMessage')
                                ->orderBy('id','asc')
                                ->get();
			}
            Messages::where('thread_id', $thread_id)

                    ->where('from_user_id','<>', auth()->user()->id)
//                    ->where('is_read', 0)
                    ->update([
                                "is_read"   => 1
                            ]);
			$result =
				[
					'messages' 				=> $messages,
					'deletedMessageList'    => $deletedMessageList,
					'thread'			    => $thread,
				];
            return makeResponse(ResponseCode::SUCCESS,Message::REQUEST_SUCCESSFUL,$result);
        }
        catch (\Exception $e)
        {
            return makeResponse(ResponseCode::FAIL,$e->getMessage());
        }
    }
}
