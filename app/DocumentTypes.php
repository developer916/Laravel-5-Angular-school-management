<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DocumentTypes extends Model
{
    protected $table = 'document_types';

    protected $primaryKey = 'id';

    public $timestamps = false;

}
