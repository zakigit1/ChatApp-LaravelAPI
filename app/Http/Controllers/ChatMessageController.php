<?php

namespace App\Http\Controllers;

use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class ChatMessageController extends Controller
{
    /**
     * Gets chat message
     *
     * @param GetMessageRequest $request
     * @return JsonResponse
     */
    public function index(GetMessageRequest $request): JsonResponse
    {

        // naddo ra9m dicussion : 
        $data = $request->validated();

        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId)
            ->with(['user' => function ($q) {
                $q->select('id', 'username');
            }])
            ->latest('created_at')
            ->simplePaginate(
                $pageSize,
                ['*'],
                'page',
                $currentPage
            );
            
            $messages->makeHidden(['id','user_id','chat_id']);

          

        return $this->success($messages->getCollection());
    }

    /**
     * Create a chat message
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */


    public function store(StoreMessageRequest $request) : JsonResponse
    {
        $data = $request->validated();
        
        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        
        // $chatMessage->load('user');

        $chatMessage->load(['user' => function ($q) {
            $q->select('id','username');
        }]);

        $chatMessage->makeHidden(['id','user_id','chat_id']);

        
        /// TODO send broadcast event to pusher and send notification to onesignal services
        $this->sendNotificationToOther($chatMessage);

        return $this->success($chatMessage,'Message has been sent successfully.');
    }

    /**
     * Send notification to other users
     *
     * @param ChatMessage $chatMessage
     */
    private function sendNotificationToOther(ChatMessage $chatMessage) : void {

        // TODO move this event broadcast to observer
        broadcast(new NewMessageSent($chatMessage))->toOthers();

        $user = auth()->user();
        $userId = $user->id;

        // if you want to passe a variable to function you need to use : use($variable)
        $chat = Chat::where('id',$chatMessage->chat_id)
            ->with(['participants'=>function($query) use ($userId){
                $query->where('user_id','!=',$userId);
            }])
            ->first();

        /* 
            bach ntakdo bli kayn participants with me to send to him the notification: 
            fl hala t3na kayn ghi zouj ana we lm3aya khtrch dicussion private 
        */ 
        if(count($chat->participants) > 0){

            $otherUserId = $chat->participants[0]->user_id;

            $otherUser = User::where('id',$otherUserId)->first();//data of other user

            // we send the notification to other user
            $otherUser->sendNewMessageNotification([//this is a func drnaha fl model user bach trsl data ll onesignal notification 
                'messageData'=>[
                    'chatId'=>$chatMessage->chat_id,
                    'senderName'=>$user->username,
                    'message'=>$chatMessage->message
                ]
            ]);
        }
    }
}