<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class universityTerms extends Model
{
    protected $table = 'university_terms';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
