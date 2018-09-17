<?php
namespace App\Http\Controllers;

use App\Models\User;
//use App\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ChatController extends BaseController
{
    /**
     * @param Chat $chat
     */
    public function __construct(Chat $chat)
    { 
        $this->chat = $chat;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->chat->all();
    }
    
    /**
     * @param Chat $chat
     * @return $chat
     */
    public function show($user_id, User $user)
    {
        //$chat = $chat->find($id);
        $user = $user->find($user_id);
        //$chat->user_id = $user->id;
        return $user;
    }
    
    /**
     * @return static
     */
    public function create()
    {
        return $this->chat->create(Input::all());
    }
}
