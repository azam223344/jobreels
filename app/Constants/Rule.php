<?php
 
namespace App\Constants;

class Rule
{
    // Rules According to API's
	private static $rules = [
        'CHECK_NUMBER' => [
            'phone'         =>'required',
        ],
        'CITIES' =>[
            'state_id'         =>'required',
        ],
        'PROFILE' => [
            'id'         =>'required',
        ],
        'ADD_THREAD' => [
            'from_user_id'    => 'required|exists:users,id',
            'to_user_id'      => 'required|exists:users,id',
        ],
        'CLEAR_THREAD' => [
            'thread_id'    => 'required',
        ],
        'DELETE_THREAD' => [
            'thread_id'    => 'required',
        ],
        'LIST_MESSAGE' => [
            'thread_id'       => 'required|exists:threads,id',
        ],
        'MARK_MESSAGES_READ' => [
            'thread_id'       => 'required|exists:threads,id',
        ],
        'DELETE_MESSAGE'     => [
            'message_number' => 'required|exists:messages,message_number',
        ],
	];

	public static function get($api){
		return self::$rules[$api];
	}
}