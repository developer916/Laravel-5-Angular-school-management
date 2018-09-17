<?php

namespace App\Http\Controllers;

use App\Models\User;
//use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
//use App\Repositories\User;

class UserController extends BaseController
{
    /**
     * The user repository instance.
     */
    protected $users;
    
    /**
     * @param User $users
     */
    public function __construct(User $users)
    {
        $this->users = $users;
    }
    
    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(User $users)
    {
        /*if(Auth::check()){
            if(Auth::user()->type == 'school'){
                return $this->users->where('type', 'student')->get(); 
            }else{
                return $this->users->where('type', 'school')->get(); 
            }
        }*/
        
        if(Auth::check()){
            if(Auth::user()->type == 'school'){
                return $this->users->select(DB::raw('users.*, messages.text, messages.created_at as msg_time, (SELECT COUNT(m.id) FROM messages m WHERE m.user_receiver_id='.Auth::user()->id.' AND m.user_sender_id=users.id AND m.status=0 ) AS unread'))
            					->where('users.type', 'student')
            					->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
            					->where(function($query)
					            {
					                $query->whereIn('messages.id',function($subQuery){
			                                   	 		$subQuery->select(DB::raw('MAX(id) AS id'))
			                                            ->from('messages')
			                                            ->where(['user_receiver_id' => Auth::user()->id])
			                                            ->groupBy('user_sender_id');
			                                })
					                      ->orWhere('messages.user_sender_id', '=', null);
					            })
            				    ->orderBy('messages.created_at', 'desc')
            					->get(); 
            }else{
                return $this->users->select(DB::raw('users.*, messages.text, messages.created_at as msg_time, (SELECT COUNT(m.id) FROM messages m WHERE m.user_receiver_id='.Auth::user()->id.' AND m.user_sender_id=users.id AND m.status=0 ) AS unread'))
				                ->where('type', 'school')
				                ->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
            					->where(function($query)
					            {
					                $query->whereIn('messages.id',function($subQuery){
			                                   	 		$subQuery->select(DB::raw('MAX(id) AS id'))
			                                            ->from('messages')
			                                            ->where(['user_receiver_id' => Auth::user()->id])
			                                            ->groupBy('user_sender_id');
			                                })
					                      ->orWhere('messages.user_sender_id', '=', null);
					            })
            				    ->orderBy('messages.created_at', 'desc')
				                ->get(); 
        }
        }
    }
    
    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUsersSearchByName(Request $request, User $users)
    {
        if(Auth::check()){
        	$user_id = Auth::user()->id;
            if(Auth::user()->type == 'school'){
                return $this->users->select(DB::raw('users.*, messages.text, messages.created_at as msg_time, (SELECT COUNT(m.id) FROM messages m WHERE m.user_receiver_id='.Auth::user()->id.' AND m.user_sender_id=users.id AND m.status=0 ) AS unread'))
                				->where('type', 'student')
                				
				                /*->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
                				->whereIn('messages.id',function($subQuery){
                                    $subQuery->select(DB::raw('MAX(id) AS id'))
                                            ->from('messages')
                                            ->where(['user_receiver_id' => Auth::user()->id])
                                            ->groupBy('user_sender_id');
                                })
                                ->where('messages.user_receiver_id','=',Auth::user()->id)*/
                                ->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
            					->where(function($query)
					            {
					                $query->whereIn('messages.id',function($subQuery){
			                                   	 		$subQuery->select(DB::raw('MAX(id) AS id'))
			                                            ->from('messages')
			                                            ->where(['user_receiver_id' => Auth::user()->id])
			                                            ->groupBy('user_sender_id');
			                                })
					                      ->orWhere('messages.user_sender_id', '=', null);
					            })
            				    ->orderBy('messages.created_at', 'desc')
                                ->where('name', 'like', '%' . $request->name . '%')
				                ->get(); 
            }else{
                return $this->users->select(DB::raw('users.*, messages.text, messages.created_at as msg_time, (SELECT COUNT(m.id) FROM messages m WHERE m.user_receiver_id='.Auth::user()->id.' AND m.user_sender_id=users.id AND m.status=0 ) AS unread'))
                				->where('type', 'school')
                				/*->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
                				->whereIn('messages.id',function($subQuery){
                                    $subQuery->select(DB::raw('MAX(id) AS id'))
                                            ->from('messages')
                                            ->where(['user_receiver_id' => Auth::user()->id])
                                            ->groupBy('user_sender_id');
                                })
                                ->where('messages.user_receiver_id','=',Auth::user()->id)*/
                                ->leftJoin('messages', 'users.id', '=', 'messages.user_sender_id')
            					->where(function($query)
					            {
					                $query->whereIn('messages.id',function($subQuery){
			                                   	 		$subQuery->select(DB::raw('MAX(id) AS id'))
			                                            ->from('messages')
			                                            ->where(['user_receiver_id' => Auth::user()->id])
			                                            ->groupBy('user_sender_id');
			                                })
					                      ->orWhere('messages.user_sender_id', '=', null);
					            })
            				    ->orderBy('messages.created_at', 'desc')
                                ->where('name', 'like', '%' . $request->name . '%')
                				->get(); 
            }
        }       
    }
    
    /**
     * @param User $user
     * @return $user
     */
    public function show($id, User $user)
    {
        return $user->find($id);
    }

    
    //public function loginKareem()
    //{
    //    $user =  Auth::loginUsingId(1);
    //    if(! $user){
    //        throw new Exception('Error logging in'); 
    //    }
    //}
    //
    //public function loginMohamed()
    //{
    //    $user =  Auth::loginUsingId(2);
    //    if(! $user){
    //        throw new Exception('Error logging in'); 
    //    }     
    //}
}
