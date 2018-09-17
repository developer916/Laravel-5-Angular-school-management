<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class refCountry extends Model
{
    protected $table = 'ref_country';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * @return  Illuminate\Database\Eloquent\Relations\HasMany
     */    
    //public function messages(){
    //    return $this->hasMany('Message', 'ref_country_id');
    //}  
}
