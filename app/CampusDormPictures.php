<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusDormPictures extends Model
{
    protected $table = 'campus_dorm_pictures';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
