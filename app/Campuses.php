<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Universities;
use App\addresses;
use App\CampusNeeds;
use App\CampusRoomTypes;
use App\CampusRoomTypesAmenities;
use App\CampusesAmenities;
use App\CampusPictures;
use App\CampusRoomTypePictures;
use App\CampusDormPictures;
use App\CampusDormItems;


class Campuses extends Model
{
    protected $table = 'campuses';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Get Campuses by Scope  ...
     *
     * @return Mixed
     */
    public function scopeByCampuses($query, $university_id){
        return $query->where('university_id',$university_id);
    }
    
    /**
     * Get Campuses by University id ...
     * 
     * @return Mixed
     */
    public function getCampusesData($university_id)
    {
        $campusesData = $this->byCampuses($university_id)->get();
        foreach($campusesData as $key=>$campus){
            if(!empty($campus->address_id)){
                $addresses = addresses::find($campus->address_id);
                //$campusesData[$key]->address = $addresses->address_name;
                $campusesData[$key]->address = $addresses;
            }
            
            //Get Campus Needs by campus id...
            $campusNeeds = CampusNeeds::where('campus_id', $campus->id)->first();
            if(!empty($campusNeeds)){
                $campusesData[$key]->name = $campusNeeds->name; 
            }
            
            //Get Campus Room Types by campus id...
            $campusRoomTypes = CampusRoomTypes::where('campus_id', $campus->id)->get();
            if(!empty($campusRoomTypes)){
                $campusesData[$key]->roomObj = $campusRoomTypes;   
                foreach($campusRoomTypes as $rkey=>$roomTypes){
                	
                	//Get Campus Room Types Amenities by campus_room_type_id...
                    $campusRoomTypesAmenities = CampusRoomTypesAmenities::where('campus_room_type_id', $roomTypes->id)->get();
                    if(!empty($campusRoomTypesAmenities)){
                        $campusesData[$key]->roomObj[$rkey]->room_amenities = $campusRoomTypesAmenities;
                    }
                    
                    //Get Campus Room Types Pictures by campus_room_type_id...
                    $campusRoomTypePictures = CampusRoomTypePictures::where('campus_room_type_id', $roomTypes->id)->get();
                    if(!empty($campusRoomTypePictures)){
                        $campusesData[$key]->roomObj[$rkey]->campusRoomTypePictures = $campusRoomTypePictures;
                    }
                }    
            }
            
            //Get Campus Dorm Amenities by campus id...
            $campusesAmenities = CampusesAmenities::where('campus_id', $campus->id)->get();
            if(!empty($campusesAmenities)){
                $campusesData[$key]->drom_amenities = $campusesAmenities;
            }
            
            //Get Campus Dorm Pictures by campus id...
            $campusDormPictures = CampusDormPictures::where('campus_id', $campus->id)->get();
            if(!empty($campusDormPictures)){
                $campusesData[$key]->campusDormPictures = $campusDormPictures;
            }
            
            //Get Campus Dorm Items by campus id...
            $campusDormItems = CampusDormItems::where('campus_id', $campus->id)->get();
            if(!empty($campusDormItems)){
                $campusesData[$key]->campusDormItems = $campusDormItems;
            }
            
            //Get Campus Pictures by campus id...
            $campusPictures = CampusPictures::where('campus_id', $campus->id)->get();
            if(!empty($campusPictures)){
                $campusesData[$key]->campusPictures = $campusPictures;
            }
        }
        return $campusesData;
    }
      
    /**
     * Get Campus by Campus id ...
     * @param $campus_id
     * @return Mixed
     */
    public function getCampusByIdData($campus_id)
    {
    	//Get Campus by id using find method
        $campusData = $this->find($campus_id);
        
        //Get Universities by id using find method
        $university = Universities::find($campusData->university_id);
		
        if(Auth::user()->id == $university->user_id){	
            
            // Get address Detail by id ....
            if(!empty($campusData->address_id)){
                $addresses = addresses::find($campusData->address_id);
                $campusData->address = $addresses;
            }
            
            // Get Campus needs by campus_id ...
            $campusNeeds = CampusNeeds::where('campus_id', $campusData->id)->first();
            if(!empty($campusNeeds)){
                $campusData->name = $campusNeeds->name; 
            }
            
            // Get Campus RoomTypes by campus_id ...
            $campusRoomTypes = CampusRoomTypes::where('campus_id', $campusData->id)->get();
            if(!empty($campusRoomTypes)){
                $campusData->roomObj = $campusRoomTypes;   
                foreach($campusRoomTypes as $rkey=>$roomTypes){
                    // Get Campus RoomTypes Amenities by roomTypes_id ...
                    $campusRoomTypesAmenities = CampusRoomTypesAmenities::where('campus_room_type_id', $roomTypes->id)->get();
                    if(!empty($campusRoomTypesAmenities)){
                    	$campusData->roomObj[$rkey]->roomAmenities = $campusRoomTypesAmenities;
                    	$roomAmenitiesObj = array();
                    	foreach($campusRoomTypesAmenities as $roomAmenities){
                    		if($roomAmenities->is_checked){
								$roomAmenities->is_checked = true;	
							}else{
								$roomAmenities->is_checked = false;	
							}
							$roomAmenitiesObj[$roomAmenities->amenity_id] = $roomAmenities->is_checked;	
						}
						$campusData->roomObj[$rkey]->room_amenities = $roomAmenitiesObj;
                        
                    }
                    
                    // Get Campus RoomTypes Pictures by roomTypes_id ...
                    $campusRoomTypePictures = CampusRoomTypePictures::where('campus_room_type_id', $roomTypes->id)->get();
                    if(!empty($campusRoomTypePictures)){
                        $campusData->roomObj[$rkey]->campusRoomType_pictures = $campusRoomTypePictures;
                    }    
                }    
            }
            
            // Get Campus Amenities by campus_id ...
            $campusesAmenities = CampusesAmenities::where('campus_id', $campusData->id)->get();
            if(!empty($campusesAmenities)){
                //$campusData->drom_amenities = $campusesAmenities;
                $campusData->dromAmenities = $campusesAmenities;
            	$dromAmenitiesObj = array();
            	foreach($campusesAmenities as $dromAmenities){
            		if($dromAmenities->is_checked){
						$dromAmenities->is_checked = true;	
					}else{
						$dromAmenities->is_checked = false;	
					}
					$dromAmenitiesObj[$dromAmenities->amenity_id] = $dromAmenities->is_checked;	
				}
				$campusData->drom_amenities = $dromAmenitiesObj;
            }
            
            //Get Campus Dorm Pictures by campus id...
            $campusDormPictures = CampusDormPictures::where('campus_id', $campusData->id)->get();
            if(!empty($campusDormPictures)){
                $campusData->campusDormPictures = $campusDormPictures;
            }
            
            //Get Campus Dorm Items by campus id...
            $campusDormItems = CampusDormItems::where('campus_id', $campusData->id)->get();
            if(!empty($campusDormItems)){
                $campusData->campusDormItems = $campusDormItems;
            }
            
            // Get Campus Pictures by campus_id ...
            $campusPictures = CampusPictures::where('campus_id', $campusData->id)->get();
            if(!empty($campusPictures)){
                $campusData->campusPictures = $campusPictures;
            }
        }
        return $campusData;
    }
    
    /**
     * Save Campuses Data by University id ...
     * @param $data, $university_id
     * @return Mixed
     */
    public function saveCampusesData($data, $university_id) {
    	
        if(!empty($data['id'])){
        	
			//Get Campus by id using find method
        	$campusData = $this->find($data['id']);
			
			$update = array();
			if(!empty($data['description']))
                $update['description'] = $data['description'];
            if(!empty($data['accomodation']))
                $update['accomodation'] = $data['accomodation'];
            
            // Update Address ....    
			if(!empty($campusData->address_id)){
				$addresses = new addresses;
				//$refCountry = refCountry::find($profile['ref_country_id']);
                //$ref_cntry_name = !empty($refCountry->ref_cntry_name) ? $refCountry->ref_cntry_name : "";
                $campus_address = $data['address'];
                //$latlng = $addresses->getLatLng($profile['address']);
                $latlng = $addresses->getLatLng($campus_address);
                
                $addresses->where('id', $campusData->address_id)->update([
                            'address_name' => $data['address'],
                            'lat'          => !empty($latlng['lat'])? $latlng['lat'] : '',
                            'lng'          => !empty($latlng['lng'])? $latlng['lng'] : '',
                        ]);
			}else{
				$addresses = new addresses;
		        $latlng = $addresses->getLatLng($data['address']);
		        $addresses->address_name = $data['address'];
		        $addresses->lat = !empty($latlng['lat'])? $latlng['lat'] : '';
		        $addresses->lng = !empty($latlng['lng'])? $latlng['lng'] : '';
		        $addresses->save();
		        $update['address_id'] = $addresses->id;
			}
			
			// Update Campus name ....
			$campusNeeds = CampusNeeds::where('campus_id',$campusData->id)->first();
	        if(!empty($campusNeeds)){
				CampusNeeds::where('campus_id',$campusData->id)->update([
                                'name' => $data['name']
                            ]);
			}else{
				$campusNeeds = new CampusNeeds;
		        $campusNeeds->campus_id = $campusData->id;
		        $campusNeeds->name = $data['name'];
		        $campusNeeds->save();
			}
	        
	        //Update Campus Rooms Detail...
	        if(!empty($data['room_type'])){    
	            foreach($data['roomObj'] as $key=>$val){
	                if($data['room_type'] > $key){
	                	$campusRoomTypesId = !empty($val['id'])? $val['id'] : 0 ;
	                	$campusRoomTypes = CampusRoomTypes::find($campusRoomTypesId);
	                	 //Update Campus Room Types ....
	                	if(!empty($campusRoomTypes)){
							CampusRoomTypes::where('id',$campusRoomTypes->id)->update([
                                'type' 	=> $val['type'],
                                'price' => $val['price'],
                                'fit' 	=> $val['fit'],
                                'size' 	=> $val['size'],
                            ]);
						}else{
						    $campusRoomTypes = new CampusRoomTypes;
		                    $campusRoomTypes->campus_id         = $campusData->id;
		                    $campusRoomTypes->ref_room_type_id  = 0;
		                    $campusRoomTypes->type              = $val['type'];
		                    $campusRoomTypes->price             = $val['price'];
		                    $campusRoomTypes->fit               = $val['fit'];
		                    $campusRoomTypes->size              = $val['size'];
		                    $campusRoomTypes->save();
		                }
		                //Update Campus Room Amenities ....
	                    if(!empty($val['room_amenities'])){
	                        foreach($val['room_amenities'] as $rkey=>$rVal){
	                        	$campusRoomTypesAmenities = CampusRoomTypesAmenities::where(['campus_room_type_id'=>$campusRoomTypes->id,'amenity_id'=>$rkey])->first();
	                        	if(!empty($campusRoomTypesAmenities)){
									CampusRoomTypesAmenities::where(['campus_room_type_id'=>$campusRoomTypes->id,'amenity_id'=>$rkey])->update(['is_checked' => $rVal]);
								}else{
								    $campusRoomTypesAmenities = new CampusRoomTypesAmenities;
		                            $campusRoomTypesAmenities->campus_room_type_id  = $campusRoomTypes->id;
		                            $campusRoomTypesAmenities->amenity_id           = $rkey;
		                            $campusRoomTypesAmenities->is_checked           = $rVal;
		                            $campusRoomTypesAmenities->save();
	                            }
	                        }
	                    }
	                    //Update Campus Room pictures ....
	                    if(!empty($val['campusRoomTypePictures'])){
	                        foreach($val['campusRoomTypePictures'] as $rVal){
	                            $campusRoomTypePictures = new CampusRoomTypePictures;
	                            $campusRoomTypePictures->campus_room_type_id  = $campusRoomTypes->id;
	                            $campusRoomTypePictures->link                 = $rVal;
	                            $campusRoomTypePictures->save();
	                        }
	                    }
	                }
	            }
	        }
	        
	        //Update Drom amenities...
			if(!empty($data['drom_amenities'])){
			    foreach($data['drom_amenities'] as $dkey=>$dVal){
			    	$campusesAmenities = CampusesAmenities::where(['campus_id'=>$campusData->id,'amenity_id'=>$dkey])->first();
			    	if(!empty($campusesAmenities)){
						CampusesAmenities::where(['campus_id'=>$campusData->id,'amenity_id'=>$dkey])->update([
                                'is_checked' => $dVal
                            ]);
					}else{
					    $campusesAmenities = new CampusesAmenities;
		                $campusesAmenities->campus_id    = $campusData->id;
		                $campusesAmenities->amenity_id   = $dkey;
		                $campusesAmenities->is_checked   = $dVal;
		                $campusesAmenities->save();
	                }
	            }
	        }
	        
	        //Update Campus Dorm Pictures...
	        if(!empty($data['campusDormPictures'])){
	            foreach($data['campusDormPictures'] as $dkey=>$dVal){
	                $campusDormPictures = new CampusDormPictures;
	                $campusDormPictures->campus_id  = $campusData->id;
	                $campusDormPictures->link       = $dVal;
	                $campusDormPictures->save();
	            }
	        }
	        
	        //Update Campus Dorm Items...
	        if(!empty($data['dormItems'])){
	            foreach($data['dormItems'] as $dkey=>$dVal){
	            	if(!empty($dVal['name'])){
		            	if(!empty($dVal['id'])){
							/*CampusDormItems::where('id',$dVal['id'])->update([
                                'name' => $dVal['name']
                            ]);*/
                            $campusDormItems = CampusDormItems::find($dVal['id']);
			                $campusDormItems->name       = $dVal['name'];
			                $campusDormItems->save();
						}else{
						    $campusDormItems = new CampusDormItems;
			                $campusDormItems->campus_id  = $campusData->id;
			                $campusDormItems->name       = $dVal['name'];
			                $campusDormItems->save();
						}
					}
	            }
	        }
	        
	        //Update campus Pictures ....
	        if(!empty($data['campusPictures'])){
	            foreach($data['campusPictures'] as $dkey=>$dVal){
            	    $campusPictures = new CampusPictures;
	                $campusPictures->campus_id  = $campusData->id;
	                $campusPictures->link       = $dVal;
	                $campusPictures->save();
	            }
	        }
			
			// Update Campuses Data by campus id...
			if(!empty($update))
                $result = $this->where('id', $campusData->id)->update($update);
            return $campusData->id;
		}else{
			//insert campus Addresss Detail...
	        $addresses = new addresses;
	        $latlng = $addresses->getLatLng($data['address']);
	        $addresses->address_name = $data['address'];
	        $addresses->lat = !empty($latlng['lat'])? $latlng['lat'] : '';
	        $addresses->lng = !empty($latlng['lng'])? $latlng['lng'] : '';
	        $addresses->save();
	       
	       	//insert campus Detail...
	        $this->university_id 	= $university_id;
	        $this->address_id 	        = $addresses->id;
	        $this->description 	        = $data['description'];
	        $this->accomodation 	= $data['accomodation'];
	        $this->save();
	        $campus_id = $this->id;
	        
	        //insert Campus Name...
	        $campusNeeds = new CampusNeeds;
	        $campusNeeds->campus_id = $campus_id;
	        $campusNeeds->name = $data['name'];
	        $campusNeeds->save();
	        
	        //Insert Room detail...
	        if(!empty($data['room_type'])){    
	            foreach($data['roomObj'] as $key=>$val){
	                if($data['room_type'] > $key){
	                    //Insert Campus Room Types...
	                    $campusRoomTypes = new CampusRoomTypes;
	                    $campusRoomTypes->campus_id         = $campus_id;
	                    $campusRoomTypes->ref_room_type_id  = 0;
	                    $campusRoomTypes->type              = $val['type'];
	                    $campusRoomTypes->price             = $val['price'];
	                    $campusRoomTypes->fit               = $val['fit'];
	                    $campusRoomTypes->size              = $val['size'];
	                    $campusRoomTypes->save();
	                    $campusRoomTypes_id = $campusRoomTypes->id;
	                    
	                    //Insert Campus Room Amenities...
	                    if(!empty($val['room_amenities'])){
	                        foreach($val['room_amenities'] as $rkey=>$rVal){
	                            $campusRoomTypesAmenities = new CampusRoomTypesAmenities;
	                            $campusRoomTypesAmenities->campus_room_type_id  = $campusRoomTypes_id;
	                            $campusRoomTypesAmenities->amenity_id           = $rkey;
	                            $campusRoomTypesAmenities->is_checked           = $rVal;
	                            $campusRoomTypesAmenities->save();
	                        }
	                    }
	                    
	                    //Insert Campus Room Typs Pictures...
	                    if(!empty($val['campusRoomTypePictures'])){
	                        foreach($val['campusRoomTypePictures'] as $rVal){
	                            $campusRoomTypePictures = new CampusRoomTypePictures;
	                            $campusRoomTypePictures->campus_room_type_id  = $campusRoomTypes_id;
	                            $campusRoomTypePictures->link                 = $rVal;
	                            $campusRoomTypePictures->save();
	                        }
	                    }
	                }
	            }
	        }
	        
	        //Insert Campus drom Amenities...
	        if(!empty($data['drom_amenities'])){
	            foreach($data['drom_amenities'] as $dkey=>$dVal){
	                $campusesAmenities = new CampusesAmenities;
	                $campusesAmenities->campus_id    = $campus_id;
	                $campusesAmenities->amenity_id   = $dkey;
	                $campusesAmenities->is_checked   = $dVal;
	                $campusesAmenities->save();
	            }
	        }
	        
	        //Insert Campus Dorm Pictures...
	        if(!empty($data['campusDormPictures'])){
	            foreach($data['campusDormPictures'] as $dkey=>$dVal){
	                $campusDormPictures = new CampusDormPictures;
	                $campusDormPictures->campus_id  = $campus_id;
	                $campusDormPictures->link       = $dVal;
	                $campusDormPictures->save();
	            }
	        }
	        
	        //Insert Campus Dorm Items...
	        if(!empty($data['dormItems'])){
	            foreach($data['dormItems'] as $dkey=>$dVal){
	            	if(!empty($dVal['name'])){
					    $campusDormItems = new CampusDormItems;
		                $campusDormItems->campus_id  = $campus_id;
		                $campusDormItems->name       = $dVal['name'];
		                $campusDormItems->save();
					}
	            }
	        }
	        
	        //Insert Campus Pictures...
	        if(!empty($data['campusPictures'])){
	            foreach($data['campusPictures'] as $dkey=>$dVal){
	                $campusPictures = new CampusPictures;
	                $campusPictures->campus_id  = $campus_id;
	                $campusPictures->link       = $dVal;
	                $campusPictures->save();
	            }
	        }
        }
        return $campus_id;
    }
    
    /**
     * Delete Campuses Data by Campuse id ...
     * @param $campuse id
     * @return true or false
     */
    public function deleteCampuseData($campus_id) {
    	if(!empty($campus_id)){
			$campusData = $this->find($campus_id);
			if(!empty($campusData->address_id)){
				addresses::destroy($campusData->address_id);
			}
			$this->destroy($campus_id);
			CampusNeeds::where('campus_id',$campus_id)->delete();
			
			$campusRoomTypes = CampusRoomTypes::where('campus_id',$campus_id)->get();
			if(!empty($campusRoomTypes)){
				foreach($campusRoomTypes as $val){
					CampusRoomTypesAmenities::where('campus_room_type_id',$val->id)->delete();
					CampusRoomTypePictures::where('campus_room_type_id',$val->id)->delete();
				}
			}
			CampusRoomTypes::where('campus_id',$campus_id)->delete();
			CampusesAmenities::where('campus_id',$campus_id)->delete();
			CampusDormPictures::where('campus_id',$campus_id)->delete();
			CampusDormItems::where('campus_id',$campus_id)->delete();
			CampusPictures::where('campus_id',$campus_id)->delete();
			
			return true;
		}else{
			return false;
		}
	}
}
