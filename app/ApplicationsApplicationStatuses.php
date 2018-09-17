<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ApplicationsApplicationStatuses extends Model
{
    protected $table = 'applications_application_statuses';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
}
