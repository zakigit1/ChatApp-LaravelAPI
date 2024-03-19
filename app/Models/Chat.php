<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;


    protected $table = "chats";
    protected $guarded = ['id'];

    protected $hidden =[
        'created_at',
        'updated_at'
    ];



    ############## Relation : 
    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id','id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id','id');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class, 'chat_id','id')->latest('updated_at');
    }


    ################ scope : 

    public function scopeHasParticipant($query, int $userId){

        return $query->whereHas('participants', function($q) use ($userId){

            $q->where('user_id',$userId);// get all participants of this user
            
        });

    }






}
