<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampusDormItems extends Model
{
    protected $table = 'campus_dorm_items';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
