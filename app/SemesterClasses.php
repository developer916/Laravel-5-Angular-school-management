<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterClasses extends Model
{
    protected $table = 'semester_classes';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
}
