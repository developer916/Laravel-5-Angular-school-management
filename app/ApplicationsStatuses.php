<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ApplicationsStatuses extends Model
{
    protected $table = 'application_statuses';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
}
