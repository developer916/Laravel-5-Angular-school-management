<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusRoomTypes extends Model
{
    protected $table = 'campus_room_types';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
