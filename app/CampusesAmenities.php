<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusesAmenities extends Model
{
    protected $table = 'campuses_amenities';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
