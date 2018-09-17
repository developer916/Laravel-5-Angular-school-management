<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     * @var string
     */
    protected $table = 'chat_rooms';

    /**
     * @var array
     */
    protected $fillable = ['name'];
    
    /**
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * @return  Illuminate\Database\Eloquent\Relations\HasMany
     */    
    //public function messages(){
    //    return $this->hasMany('Message', 'chat_room_id');
    //}  
}
