<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;


class ChatController extends Controller
{
    /**
     * Gets chats
     *
     * @param GetChatRequest $request
     * @return JsonResponse
     */
    public function index(GetChatRequest $request): JsonResponse
    {
        $data = $request->validated();

        $isPrivate = 1;// by default is private chat 
        if ($request->has('is_private')) {
            $isPrivate = (int)$data['is_private'];
        }

        $userAuth= auth()->user(); //the user is authenticated

        $chats = Chat::where('is_private', $isPrivate)
            ->hasParticipant($userAuth->id)//scope tl9aha fl chat model 
            ->whereHas('messages')//l3ndhom message bintahom
            ->with('lastMessage.user', 'participants.user')
            ->latest('updated_at')
            ->get();

        return $this->success($chats);
    }


    /**
     * Stores a new chat
     *
     * @param StoreChatRequest $request
     * @return JsonResponse
     */
    public function store(StoreChatRequest $request) : JsonResponse
    {
        $data = $this->prepareStoreData($request);
        // this is the data from this line : $data = $this->prepareStoreData($request);
        // [
        //     'otherUserId' => $otherUserId,
        //     'userId' => auth()->user()->id,
        //     'data' => $data,
        // ]

        if($data['userId'] === $data['otherUserId']){

            return $this->error('You can not create a chat with yourself');

        }

        $previousChat = $this->getPreviousChat($data['userId'],$data['otherUserId']);
        // return response()->Json($previousChat);//yrdlak id ta3 dicussion w les creya dicussion wzadt tani lrahom participant fih (lrahom dkhlin fiha)

        if($previousChat === null){// check if doen't have a  chat(dicussion) between the user-auth and the other user

            $chat = Chat::create($data['data']);//dirah fl services 
            //$data['data'] =>['user_id','is_private','name']


            // two users les rahom mchtarkin fl same chat 
            $chat->participants()->createMany([
                [
                    'user_id'=>$data['userId']
                ],
                [
                    'user_id'=>$data['otherUserId']
                ]
            ]);



            // $chat->refresh()->load('lastMessage.user','participants.user'):
            $chat->refresh()->load('participants.user');//nrmlment mnsha9och lastmessage khtrach discussion jdida drwk cryinaha
            //kon kona ndiro get kima kona ndiro fl index ndiro chat::with('participant.usrer'...)

            return $this->success($chat);
        }

        //id cont hadar deja aya raslto message binatkom fl disscusion : 
        return $this->success($previousChat->load('lastMessage.user'));//ida dart with('participants.user') fl fun ta3 previousChat;
        // return $this->success($previousChat->load('lastMessage.user','participants.user'));
        
    }

    /**
     * Check if user and other user has previous chat or not
     *
     * @param int $otherUserId
     * @return mixed
     */
    private function getPreviousChat(int $userId ,int $otherUserId) : mixed {

        // $userId = auth()->user()->id;//rsltah 3an tri9 function parameter 

        return Chat::with('participants.user')
            //condition :
            ->where('is_private',1)
            ->whereHas('participants', function ($query) use ($userId){
                $query->where('user_id',$userId);//get who is paraticipent with user auth
            })
            ->whereHas('participants', function ($query) use ($otherUserId){
                $query->where('user_id',$otherUserId);//get who is paraticipent with otheruser
            })
            ->first();

        // same method but using scope : 
        // return Chat::where('is_private',1)
        //     ->HasParticipant($userId)
        //     ->HasParticipant($otherUserId)
        //     ->first();
    }


    /**
     * Prepares data for store a chat
     *
     * @param StoreChatRequest $request
     * @return array
     */
    private function prepareStoreData(StoreChatRequest $request) : array
    {
        $data = $request->validated();//['user_id'=user id les rak bghi create chat m3ah ,'is_private','name']

        $otherUserId = (int)$data['user_id'];

        unset($data['user_id']);//we delete the value of user_id

        $data['created_by'] = auth()->user()->id;//chat created by the authenticated user // t9dar tbdal id bl username bach ykhrjlk ismah 

        return [
            'otherUserId' => $otherUserId,//1
            'userId' => auth()->user()->id,//1
            'data' => $data,
        ];
    }


    /**
     * Gets a single chat (chat between to users )
     *
     * @param Chat $chat
     * @return JsonResponse
     */
    public function show(Chat $chat): JsonResponse
    {
        $chat->load('lastMessage.user', 'participants.user');
        if($chat->name === null){
            $chat->makeHidden(['name','is_private']);
        }
        $chat->makeHidden(['created_by']);
        
        return $this->success($chat);
    }

}
