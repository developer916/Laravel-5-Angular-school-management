<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusRoomTypesAmenities extends Model
{
    protected $table = 'campus_room_types_amenities';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
