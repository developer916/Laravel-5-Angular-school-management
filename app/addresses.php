<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class addresses extends Model
{
    protected $table = 'addresses';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Get latitued and longitude By Address Name ...
     * 
     * @return latitued and longitude 
     */
    public function getLatLng($address)
    {
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        if(!empty($output->results)){
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            return array('lat' => $latitude, 'lng' => $longitude);
        }else{
            return array();
        }
        
    }
    
    
}
