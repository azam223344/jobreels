<?php

namespace App\Models;
// Carbon
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;  
class Threads extends Model
{
    protected $connection = 'mysql';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s','updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    //
    protected $table = 'threads';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message_number',
        'b_to_user',
        'b_from_user'
    ];

    /**
     * Return a set of data we want.
     *
     */
    public function getArrayResponse() {
        
        return [
            'id'            => $this->id,
            'from_user_id'  => $this->from_user_id,
            'to_user_id'    => $this->to_user_id,
            'b_from_user'     => $this->b_from_user,
            'b_to_user'     => $this->b_to_user,
        ];
    }
    
    /**
     * Realtion to "products" Table.
     *
     */
    public function FromUser()
    {
        return $this->belongsTo(User::class,'from_user_id','id');
    }
    public function UserStrike()
    {
        return $this->hasMany(UserStrike::class,'from_user_id','from_user_id');
    }
    /**
     * Realtion to "products" Table.
     *
     */
    public function ToUser()
    {
        return $this->belongsTo(User::class,'to_user_id','id');
    }
    
    public function Message()
    {
        return $this->hasMany(Messages::class,'thread_id','id');
    }
 	public function UnReadMessage()
    {
        return $this->hasMany(Messages::class,'thread_id','id')->where('is_read',0);
    }
    public static function createThread($from_user_id,$to_user_id)
    {
        $modelThread = Threads::where(function($query) use ($from_user_id,$to_user_id) {
            return $query->where('from_user_id','=',$from_user_id)
                ->where('to_user_id','=',$to_user_id);
        })
        ->orWhere(function($query) use ($from_user_id,$to_user_id) {
            return $query->where('from_user_id','=',$to_user_id)
                ->where('to_user_id','=',$from_user_id);
        })
        ->first();
     
        if(empty($modelThread))
        {
             $random_number = $from_user_id.$to_user_id.Carbon::now()->format('YmdHis');
            $thread = new self();
            $thread->from_user_id      = $from_user_id;
            $thread->to_user_id        = $to_user_id;
            $thread->d_from_user       = 0;
            $thread->d_to_user         = 0;
            $thread->save();
            Messages::create([ 
                        "from_user_id"      => $to_user_id,
                        "type"              => "Text",
                        "message"           => "Hi there 👋🏻 Welcome to Gahhak. Please ask us anything. We’re here to help.",
                        'thread_id'         => $thread->id,
                        "message_number"    => $random_number,
                    ]);    
        }
    }
	public function lastMessage()
    {
        return $this->hasOne(Messages::class,'thread_id','id')->latest()->select('id','message','thread_id','type','is_read');
    }
}
