<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusPictures extends Model
{
    protected $table = 'campus_pictures';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
