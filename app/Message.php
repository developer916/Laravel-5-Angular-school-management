<?php

namespace App;
use Auth;
use App\Models\User;
use App\Chat;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var integer
     */
    //protected $primaryKey = 'id';
    
    /**
     * @var array
     */
    protected $fillable = ['text','user_receiver_id', 'status'];
    
    /**
     * @var array
     */
    protected $with = array('user');
    
    /**
     * @param $query
     * @param $lastId
     * @return Mixed
     */
    public function scopeAfterId($query, $lastId){
        return $query->where('id', '>', $lastId);
    }
    
    /**
     * @param $query
     * @param $chat
     * @return Mixed
     */
    //public function scopeByChat($query, $chat){
    //    return $query->where('chat_room_id',$chat->id);
    //}
    
    /**
     * @param $query
     * @param $user
     * @return Mixed
     */
    public function scopeByUser($query, $user){
        
        $this->where('user_sender_id', $user->id)->update(['status' => 1]);
        
        $sender_to_receiver = ['user_sender_id' => Auth::user()->id, 'user_receiver_id' => $user->id];
        $receiver_to_sender = ['user_sender_id' => $user->id, 'user_receiver_id' => Auth::user()->id];
       
        return $query->where($sender_to_receiver)
                    ->orWhere($receiver_to_sender);
    }
    
    
    /**
     * @param $query
     * @param $user
     * @return Mixed
     */
    public function scopeByChatLastSendUserData($query){
        
        
        $chatLastSendUser = $query->whereIn('id',function($subQuery){
                                $subQuery->select(DB::raw('MAX(id) AS id'))
                                        ->from('messages')
                                        ->where(['user_receiver_id' => Auth::user()->id, 'status' => 0])
                                        ->groupBy('user_sender_id');
                            })->orderBy('id', 'desc')->limit(3)->get();
        
        if($chatLastSendUser->count() > 0 ){
            $sendUser = array();
            foreach($chatLastSendUser as $key=>$val){
                //$chatLastSendUser[$key]->created_at_new = date('d-m-y h:i A', strtotime($val->created_at));
                $chatLastSendUser[$key]->unread = $this->select(DB::raw('count(*)'))
                                            ->where(['user_sender_id' => $val->user_sender_id, 'user_receiver_id' => $val->user_receiver_id, 'status' => 0])->count();
                $sendUser[] = $val->user_sender_id;                           
            }
            $read = 3 - $chatLastSendUser->count();
            if($read){
                //$readChatLastSendUser = $this->where(['user_receiver_id' => Auth::user()->id, 'status' => 1])->orderBy('id', 'desc')->limit($read)->get();
                
                $readChatLastSendUser = $this->whereIn('id',function($subQuery){
                                            $subQuery->select(DB::raw('MAX(id) AS id'))
                                                    ->from('messages')
                                                    ->where(['user_receiver_id' => Auth::user()->id, 'status' => 1])
                                                    ->groupBy('user_sender_id');
                                        })->orderBy('id', 'desc')->limit($read)->get();
                
                $count = $chatLastSendUser->count();
                foreach($readChatLastSendUser as $val){
                    if(!in_array($val->user_sender_id, $sendUser)){
                        $chatLastSendUser[$count] = $val;
                        $chatLastSendUser[$count]->unread = 0;
                        $count++;
                    }
                }
            }    
        }else{
            //$chatLastSendUser = $this->where(['user_receiver_id' => Auth::user()->id, 'status' => 1])->orderBy('id', 'desc')->limit(3)->get();
            
            $chatLastSendUser = $this->whereIn('id',function($subQuery){
                                    $subQuery->select(DB::raw('MAX(id) AS id'))
                                            ->from('messages')
                                            ->where(['user_receiver_id' => Auth::user()->id, 'status' => 1])
                                            ->groupBy('user_sender_id');
                                })->orderBy('id', 'desc')->limit(3)->get();
        }
        
        return $chatLastSendUser;
        //return  $query->whereIn('id',function($subQuery){
        //                $subQuery->select(DB::raw('MAX(id) AS id'))
        //                        ->from('messages')
        //                        ->where(['user_receiver_id' => Auth::user()->id])
        //                        ->groupBy('user_sender_id');
        //            })->orderBy('id', 'desc')->limit(3);
    }
    
    /**
     * @return Mixed
     */    
    public function getByChatLastSendUserData(){
        
        return $this->hasMany('Message', 'user_sender_id');
    }

    /**
     * @return  Illuminate\Database\Eloquent\Relations\BelongsTo
     */    
    //public function chat(){
    //    return $this->belongsTo('App\Chat', 'chat_room_id');
    //}
    
    /**
     * @return  Illuminate\Database\Eloquent\Relations\BelongsTo
     */  
    public function UserReceiver(){
        return $this->belongsTo('App\User', 'user_receiver_id');
    }
    
    /**
     * @return  Illuminate\Database\Eloquent\Relations\BelongsTo
     */  
    public function User(){
        return $this->belongsTo('App\User', 'user_sender_id');
    }
}
