<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;
    protected $table = "chat_messages";
    protected $guarded = [
        'id'
    ];

    protected $hidden =[

        'created_at',
        'updated_at'
    ];

    /* --------------------------------$touches :  
     when i doing any modification (create of update ) he will update
     the columns update_at in the table chat also :  

     and he display the columns of the  table when i return response of chat message 
     */
    protected $touches = [
        'chat'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
