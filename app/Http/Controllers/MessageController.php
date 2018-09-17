<?php

namespace App\Http\Controllers;

use App\Message;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MessageController extends BaseController
{
    /**
     * @param Message $messages
     */
    public function __construct(Message $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param User $user
     * @param Message $messages 
     * @return mixed
     */
    public function getByChat($user_id, Message $messages, User $user)
    {
        $user = $user->find($user_id);
        //$messages->where('user_sender_id', $user_id)->update(['status' => 1]);
        return $messages->byUser($user)->get();
    }
    
    /**
     * @param User $user
     * @return $message
     */
    public function createInChat(User $user)
    {
        $message = $this->messages->newInstance(Input::all());
        $message->conversation_id = 0;
        $message->user()->associate($this->me());
        $message->save();   
        return $message;
    }
    
    /**
     * @param $lastMessageId
     * @param Message $messages
     * @param User $user
     * @return mixed
     */
    public function getUpdates($lastMessageId, $user_id, Message $messages, User $user)
    {
        $user = $user->find($user_id);
        //$messages->where('user_sender_id', $user_id)->update(['status' => 0]);
        return $messages->byUser($user)->afterId($lastMessageId)->get();
    }
    
    /**
     * @param User $user
     * @return mixed
     */
    public function getByChatLastSendUser(Message $messages, User $user)
    {
        if(Auth::check()){
            //return $messages->byChatLastSendUserData()->get();
            return $messages->byChatLastSendUserData();
        }
        
    }
}
