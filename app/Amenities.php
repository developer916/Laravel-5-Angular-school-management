<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenities extends Model
{
    protected $table = 'amenities';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
}
