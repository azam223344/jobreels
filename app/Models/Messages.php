<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class Messages extends Model
{
    protected $connection = 'mysql';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s','updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    //
    protected $table = 'messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_user_id',
        'type',
        'message',
        'thread_id',
        'file_path',
        'is_read',
        'd_from_user',
        'd_to_user'
    ];
    
    /**
     * Return a set of data we want.
     *
     */
    public function getArrayResponse() {
        
        return [
            'id'            => $this->id,
            'from_user_id'  => $this->from_user_id,
            'type'          => $this->type,
            'message'       => $this->message,
            'thread_id'     => $this->thread_id,
            'file_path'     => $this->file_path,
            'is_read'       => $this->is_read,
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
    
    /**
     * Realtion to "threads" Table.
     *
     */
    public function Thread()
    {
        return $this->belongsTo(Threads::class,'thread_id','id');
    }

    /**
     * Realtion to "products" Table.
     *
     */
    public function parentMessage()
    {
        return $this->hasOne(Messages::class, 'message_number','parent_number');
    }
    
}