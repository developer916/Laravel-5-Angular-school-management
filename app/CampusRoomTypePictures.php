<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusRoomTypePictures extends Model
{
    protected $table = 'campus_room_type_pictures';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
