<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusNeeds extends Model
{
    protected $table = 'campus_needs';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    //public function scopeByCampusNeeds($query, $campus_id){
    //    return $query->where('campus_id',$campus_id);
    //}
}
