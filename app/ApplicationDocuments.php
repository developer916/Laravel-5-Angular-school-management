<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ApplicationDocuments extends Model
{
    protected $table = 'application_documents';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
}
