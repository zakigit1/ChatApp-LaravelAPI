<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\MessageSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    protected $guarded = ['id'];

    protected $hidden = [
        // 'id',
        'password',
        'created_at',
        'updated_at',
    ];

    //this using when i create a token of user : is optional 
    const USER_TOKEN = "userToken";

    public function chats():HasMany
    {
        return $this->hasMany(Chat::class, 'created_by','id');
    }


    // public function messages(): HasMany
    // {
    //     return $this->hasMany(ChatMessage::class,'user_id','id');
    // }

    
    //this function for one signal package :
    public function routeNotificationForOneSignal() : array{
        return [
            'tags'=>[
                'key'=>'userId','relation'=>'=', 'value'=>(string)($this->id)// userId = id
            ]
        ];
    }
    
    //send notfication :
    public function sendNewMessageNotification(array $data) : void {
        $this->notify(new MessageSent($data));
    }
}
