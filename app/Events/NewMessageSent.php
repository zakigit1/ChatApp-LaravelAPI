<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;//without queue work 

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    #### Method 2
    // private ChatMessage $chatMessage;

    public function __construct(private ChatMessage $chatMessage) //Method 1 inside the ()
    {
        // if you use method 2 : 
        // $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new PrivateChannel('private.chat.'.$this->chatMessage->chat_id),
            new PrivateChannel('chat.'.$this->chatMessage->chat_id),
        ];
    }
    public function broadcastAs():string
    {
        return "message.sent";
    }
    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatMessage->chat_id,
            'message' => $this->chatMessage->toArray(),// drnah fi array khtrch y9dar ykon bzzf messages
        ];
    }
}
