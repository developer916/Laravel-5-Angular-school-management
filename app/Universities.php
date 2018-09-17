<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\addresses;
use App\refCountry;
use App\contacts;
use App\universityTerms;
use App\refTermType;
use App\Campuses;
use App\Diplomas;
//use App\RefLanguage;
use App\UniversitiesPictures;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Universities extends Model
{
    protected $table = 'universities';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * @param $query
     * @param $user_id
     * @return Mixed
     */
    public function scopeByUser($query, $user_id){
        return $query->where('user_id',$user_id);
    }
    
    /**
     * Get All Universities ...
     * 
     * @return Mixed
     */
    public function getUniversitiesData()
    {
        // Get All Universities By all function ...
        //$universitiesData = $this->all();
        $universitiesData = $this->where('address_id','!=',0)->where('ref_country_id','!=',0)->get();
        
        foreach($universitiesData as $key=>$val){
            
            // Get Address by address id of university ...
            if(!empty($val->address_id)){
                $addresses = addresses::find($val->address_id);
                $universitiesData[$key]->address = $addresses;
            }
            
            // Get Reference of country by reference country id of university ...
	        if(!empty($val->ref_country_id)){
	            $refCountry = refCountry::find($val->ref_country_id);
	            $universitiesData[$key]->refCountry = $refCountry;
	        }
            
            // Get Contacts by User id of university ...
            $contacts = contacts::where('user_id', $val->user_id)->first();
            if(!empty($contacts)){
                $universitiesData[$key]->contacts = $contacts; 
            }
            
            // Get Terms by University id ...
            $universityTerms = universityTerms::where('university_id', $val->id)->get();
            $universityTermsCount =  $universityTerms->count();
            if(!empty($universityTermsCount)){
                $universitiesData[$key]->termsCount = $universityTermsCount; 
                foreach($universityTerms as $tkey=>$terms){
                    if(!empty($terms->term_type_id)){
                        $refTermType = refTermType::find($terms->term_type_id);
                        $universityTerms[$tkey]->term_name = $refTermType->name;
                    }
                    //$universityTerms[$tkey]->start_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date)))  : date('m/d/Y');
                    //$universityTerms[$tkey]->term_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date))) : date('m/d/Y');
                    $universityTerms[$tkey]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                    $universityTerms[$tkey]->term_date = $universityTerms[$tkey]->start_date;
                }
                $universitiesData[$key]->terms = $universityTerms;
            }
            
            // Get Campuses(Housing) by University id ...
            $campuses = new Campuses(); 
            $campusesData = $campuses->getCampusesData($val->id);
            if(!empty($campusesData)){
                $universitiesData[$key]->housing = $campusesData;
            }
            
            // Get Program(Diplomas) by University id ...
            $diplomas = new Diplomas();
            $programData = $diplomas->getProgramData($val->id);
            if(!empty($programData)){
                $universitiesData[$key]->diplomas = $programData;
            }
            
            // Get pictures of University by University id
            $universitiesPictures = UniversitiesPictures::where('university_id', $val->id)->get();
            if(!empty($universitiesPictures)){
                $universitiesData[$key]->pictures = $universitiesPictures;
            }
            
        }
        
        return $universitiesData;
    }
    
    /**
     * Get University by current user id login ...
     * 
     * @return Mixed
     */
    public function getUniversityData()
    {
        $user_id = Auth::user()->id;
        $universityData = $this->byUser($user_id)->first();
        
        // Get Address by address id of university ...
        if(!empty($universityData->address_id)){
            $addresses = addresses::find($universityData->address_id);
            //$universityData->address = $addresses->address_name;
            $universityData->address = $addresses;
        }
        
        // Get Reference of country by reference country id of university ...
        if(!empty($universityData->ref_country_id)){
            $refCountry = refCountry::find($universityData->ref_country_id);
            $universityData->refCountry = $refCountry;
        }
        
        // Get Contacts by User id of university ...
        $contacts = contacts::where('user_id', $universityData->user_id)->first();
        if(!empty($contacts)){
            $universityData->phone = $contacts->value; 
        }
        
        // Get Terms by University id ...
        $universityTerms = universityTerms::where('university_id', $universityData->id)->get();
        $universityTermsCount =  $universityTerms->count();
        if(!empty($universityTermsCount)){
            $universityData->terms = $universityTermsCount; 
            foreach($universityTerms as $key=>$terms){
                if(!empty($terms->term_type_id)){
                    $refTermType = refTermType::find($terms->term_type_id);
                    $universityTerms[$key]->term_name = $refTermType->name;
                }
                //$universityTerms[$key]->start_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date)))  : date('m/d/Y');
                //$universityTerms[$key]->term_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date))) : date('m/d/Y');
                $universityTerms[$key]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                $universityTerms[$key]->term_date = $universityTerms[$key]->start_date;
            }
            $universityData->termsObj = $universityTerms;
        }
        
        // Get Campuses(Housing) by University id ...
        $campuses = new Campuses(); 
        $campusesData = $campuses->getCampusesData($universityData->id);
        if(!empty($campusesData)){
            $universityData->housing = $campusesData;
        }
        
        // Get Program(Diplomas) by University id ...
        $diplomas = new Diplomas();
        $programData = $diplomas->getProgramData($universityData->id);
        if(!empty($programData)){
            $universityData->diplomas = $programData;
        }
        
        // Get pictures of University by University id
        $universitiesPictures = UniversitiesPictures::where('university_id', $universityData->id)->get();
        if(!empty($universitiesPictures)){
            $universityData->pictures = $universitiesPictures;
        }
        
        return $universityData;
    }
    
    /**
     * Get University by University id ...
     * 
     * @return Mixed
     */
    public function getUniversityByIdData($university_id)
    {
        
        $universityData = $this->find($university_id);
        
        // Get Address by address id of university ...
        if(!empty($universityData->address_id)){
            $addresses = addresses::find($universityData->address_id);
            //$universityData->address = $addresses->address_name;
            $universityData->address = $addresses;
        }
        
        // Get Reference of country by reference country id of university ...
        if(!empty($universityData->ref_country_id)){
            $refCountry = refCountry::find($universityData->ref_country_id);
            $universityData->refCountry = $refCountry;
        }
        
        // Get Contacts by User id of university ...
        $contacts = contacts::where('user_id', $universityData->user_id)->first();
        if(!empty($contacts)){
            $universityData->phone = $contacts->value; 
        }
        
        // Get Terms by University id ...
        $universityTerms = universityTerms::where('university_id', $universityData->id)->get();
        $universityTermsCount =  $universityTerms->count();
        if(!empty($universityTermsCount)){
            $universityData->termsCount = $universityTermsCount; 
            foreach($universityTerms as $tkey=>$terms){
                if(!empty($terms->term_type_id)){
                    $refTermType = refTermType::find($terms->term_type_id);
                    $universityTerms[$tkey]->term_name = $refTermType->name;
                }
                //$universityTerms[$tkey]->start_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date)))  : date('m/d/Y');
                //$universityTerms[$tkey]->term_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date))) : date('m/d/Y');
                $universityTerms[$tkey]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                $universityTerms[$tkey]->term_date = $universityTerms[$tkey]->start_date;
                
            }
            $universityData->terms = $universityTerms;
        }
        
        // Get Campuses(Housing) by University id ...
        $campuses = new Campuses(); 
        $campusesData = $campuses->getCampusesData($universityData->id);
        if(!empty($campusesData)){
            $universityData->housing = $campusesData;
        }
        
        // Get Program(Diplomas) by University id ...
        $diplomas = new Diplomas();
        $programData = $diplomas->getProgramData($universityData->id);
        if(!empty($programData)){
            $universityData->diplomas = $programData;
        }
        
        // Get pictures of University by University id
        $universitiesPictures = UniversitiesPictures::where('university_id', $universityData->id)->get();
        if(!empty($universitiesPictures)){
            $universityData->pictures = $universitiesPictures;
        }
        
        return $universityData;
    }
    
    
    /**
     * Save Profile University by current user id login ...
     * @param $profile
     * @param $step
     * @return University id
     */
    public function saveProfileData($profile, $step) {
        $user_id = Auth::user()->id; 
        $universityData = $this->byUser($user_id)->first();       
        if(!empty($universityData)){
            
            $update = array();
            if(!empty($profile['profileimage'])){
                User::where('id', $user_id)->update([ 'img_profile_link' => $profile['profileimage'] ]);
                $update['logo_link'] = $profile['profileimage'];
            }
            if(!empty($profile['school_name']))
                $update['name'] = $profile['school_name'];
            if(!empty($profile['info']))
                $update['description'] = $profile['info'];
            if(!empty($profile['slogan']))
                $update['slogan'] = $profile['slogan'];
            if(!empty($profile['ref_country_id'])){
				$update['ref_country_id']  = $profile['ref_country_id'];
				$refCountry = refCountry::find($profile['ref_country_id']);
			}
            if(!empty($profile['address'])){
                if(!empty($universityData->address_id)){
                    $addresses = new addresses;
                    $ref_cntry_name = !empty($refCountry->ref_cntry_name) ? $refCountry->ref_cntry_name : "";
                    $profile_address = $profile['address'].' '.$ref_cntry_name;
                    //$latlng = $addresses->getLatLng($profile['address']);
                    $latlng = $addresses->getLatLng($profile_address);
                    
                    $addresses->where('id', $universityData->address_id)->update([
                                'address_name' => $profile['address'],
                                'lat'          => !empty($latlng['lat'])? $latlng['lat'] : '',
                                'lng'          => !empty($latlng['lng'])? $latlng['lng'] : '',
                            ]);
                }else{
                    $addresses = new addresses;
                    $latlng = $addresses->getLatLng($profile['address']);
                    $addresses->address_name = $profile['address'];
                    $addresses->lat = !empty($latlng['lat'])? $latlng['lat'] : '';
                    $addresses->lng = !empty($latlng['lng'])? $latlng['lng'] : '';
                    $addresses->save();
                    $update['address_id'] = $addresses->id;
                }    
            }    
            
            if(!empty($profile['phone'])){
                $contacts = contacts::where('user_id', $user_id)->first();
                if(!empty($contacts)){
                    contacts::where('user_id', $user_id)->update([
                                'value' => $profile['phone']
                            ]);
                }else{
                    $contacts = new contacts;
                    $contacts->user_id 		    = $user_id;
                    $contacts->ref_contact_type_id  = 0;
                    $contacts->value 		    = $profile['phone'];
                    $contacts->save();
                }
            }
            
            if(!empty($profile['terms'])){
                $universityTerms = universityTerms::where('university_id', $universityData->id)->get();
                $universityTermsCount =  $universityTerms->count();
                if($profile['terms'] == $universityTermsCount){
                    
                    foreach($universityTerms as $key=>$terms){    
                        if(!empty($terms->term_type_id)){
                            refTermType::where('id', $terms->term_type_id)->update([
				'name' 	=> !empty($profile['termsObj'][$key]['term_name']) ? $profile['termsObj'][$key]['term_name'] : '',
			    ]);
                            $term_type_id = $terms->term_type_id;
                        }else{
                            $refTermType = new refTermType;
                            $refTermType->name  = !empty($profile['termsObj'][$key]['term_name']) ? $profile['termsObj'][$key]['term_name'] : '';
                            $refTermType->save();
                            $term_type_id = $refTermType->id;
                        }
                        if($step == 'step-8' && !empty($profile['termsObj'][$key]['term_date'])){
                            universityTerms::where('id', $terms->id)->update([
                                'start_date' => date('Y-m-d', strtotime( str_replace("/", "-", $profile['termsObj'][$key]['term_date'] ) ) ),
                                'term_type_id' => $term_type_id,
                            ]);
                        }
                    }
                
                }else{
                
                    if($profile['terms'] > $universityTermsCount){
                        
                        $terms = ($profile['terms'] - $universityTermsCount) - 1;
                        foreach( range(0, $terms) as $term){
                            $universityTerms = new universityTerms;
                            $universityTerms->university_id     = $universityData->id;
                            $universityTerms->term_type_id      = 0;
                            $universityTerms->start_date        = date('Y-m-d');
                            $universityTerms->end_date          = date('Y-m-d');
                            $universityTerms->deadline_date     = date('Y-m-d');
                            $universityTerms->break_desc        = '';
                            $universityTerms->save();
                        }
                    
                    }elseif($profile['terms'] < $universityTerms){
                        foreach($universityTerms as $key=>$terms){
                            if($key > ($profile['terms'] - 1)){
                                universityTerms::destroy($terms->id);
                            }
                        }
                    }
                }
            }
            
            if(!empty($update))
                $result = $this->where('user_id', $user_id)->update($update);
            return $universityData->id;
        }else{
            if(!empty($profile['profileimage'])){
                User::where('id', $user_id)->update([ 'img_profile_link' => $profile['profileimage'] ]);
            }
            $this->user_id 	        = $user_id;
            $this->address_id 	        = 0;
            $this->ref_country_id 	        = 0;
            $this->logo_link 	        = !empty($profile['profileimage'])? $profile['profileimage'] : '';
            $this->name 		= !empty($profile['school_name'])? $profile['school_name'] : '';
            $this->description 	        = !empty($profile['info'])? $profile['info'] : '';
            $this->slogan 		= !empty($profile['slogan'])? $profile['slogan'] : '';
            $this->nb_student 	        = 0;
            $this->nb_student_campus    = 0;
            $this->save();
            return $this->id;
        }	   	
    }
    
    /**
     * Save University Front page by current user id login ...
     * @param $profile
     * 
     * @return University id
     */
    public function saveFrontpageData($profile) {
        $user_id = Auth::user()->id; 
        $universityData = $this->byUser($user_id)->first();       
        if(!empty($universityData)){
            if(!empty($profile['pictures'])){
                foreach($profile['pictures'] as $val){
                    $universitiesPictures = new UniversitiesPictures;
                    $universitiesPictures->university_id    = $universityData->id;
                    $universitiesPictures->link             = $val;
                    $universitiesPictures->save();
                }
            }
            return $universityData->id;
        }else{            
            $this->user_id 	        = $user_id;
            $this->address_id 	        = 0;
            $this->ref_country_id 	        = 0;
            $this->logo_link 	        = !empty($profile['profileimage'])? $profile['profileimage'] : '';
            $this->name 		= !empty($profile['school_name'])? $profile['school_name'] : '';
            $this->description 	        = !empty($profile['info'])? $profile['info'] : '';
            $this->slogan 		= !empty($profile['slogan'])? $profile['slogan'] : '';
            $this->nb_student 	        = 0;
            $this->nb_student_campus    = 0;
            $this->save();
            
            if(!empty($profile['pictures'])){
                foreach($profile['pictures'] as $val){
                    $universitiesPictures = new UniversitiesPictures;
                    $universitiesPictures->university_id    = $this->id;
                    $universitiesPictures->link             = $val;
                    $universitiesPictures->save();
                }
            }
            return $this->id;
        }	   	
    }
    
    /**
     * Get Universities By search Keyword, Where, Diploma and Speciality...
     * @param $data
     * 
     * @return Mixed
     */
    public function searchUniversitiesData($data) {
      
        // Get Universities Search By Keywords, Where, Diploma and Speciality ...
        $searchQuery = DB::table('universities as u')->select('u.*');
        if(!empty($data['keywords'])){
			//$searchQuery = $searchQuery->orWhere('u.name', 'like', '%' . $data['keywords'] . '%');
			$searchQuery = $searchQuery->where('u.name', 'like', '%' . $data['keywords'] . '%');
		}
	    if(!empty($data['ref_country_id'])){
			$searchQuery = $searchQuery->leftJoin('ref_country as rc', 'u.ref_country_id', '=', 'rc.id')
										//->orWhere('rc.id', $data['ref_country_id']);
										->where('rc.id', $data['ref_country_id']);
		}
	    if(!empty($data['diploma'])){
			$searchQuery = $searchQuery->leftJoin('diplomas as d', 'd.university_id', '=', 'u.id')
										//->orWhere('d.id', $data['diploma']);
										->where('d.id', $data['diploma']);
		}
		
		if(!empty($data['speciality'])){
			$searchQuery = $searchQuery->leftJoin('diploma_specializations as ds', 'ds.id', '=', 'd.diploma_specialization_id')
										//->orWhere('sc.id', $data['speciality']);
										->where('ds.id', $data['speciality']);
		}
	    $universitiesData = $searchQuery->orderBy('u.id', 'desc')
	    								->groupBy('u.id')
	    								->get();
        
        // Get Universities Search By Keywords, Where, Diploma and Speciality ...
        /*$searchQuery = $this;
        if(!empty($data['keyword'])){
			$searchQuery = $this->where('name', 'like', '%' . $data['keyword'] . '%');
		}
	   	$universitiesData = $searchQuery->get();
		*/
	    
	    foreach($universitiesData as $key=>$val){
            
            // Get Address by address id of university ...
            if(!empty($val->address_id)){
                $addresses = addresses::find($val->address_id);
                $universitiesData[$key]->address = $addresses;
            }
            
            // Get Reference of country by reference country id of university ...
	        if(!empty($val->ref_country_id)){
	            $refCountry = refCountry::find($val->ref_country_id);
	            $universitiesData[$key]->refCountry = $refCountry;
	        }
            
            // Get Contacts by User id of university ...
            $contacts = contacts::where('user_id', $val->user_id)->first();
            if(!empty($contacts)){
                $universitiesData[$key]->contacts = $contacts; 
            }
            
            // Get Terms by University id ...
            $universityTerms = universityTerms::where('university_id', $val->id)->get();
            $universityTermsCount =  $universityTerms->count();
            if(!empty($universityTermsCount)){
                $universitiesData[$key]->termsCount = $universityTermsCount; 
                foreach($universityTerms as $tkey=>$terms){
                    if(!empty($terms->term_type_id)){
                        $refTermType = refTermType::find($terms->term_type_id);
                        $universityTerms[$tkey]->term_name = $refTermType->name;
                    }
                    //$universityTerms[$tkey]->start_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date)))  : date('m/d/Y');
                    //$universityTerms[$tkey]->term_date = !empty($terms->start_date) ? str_replace("-", "/",date('m-d-Y', strtotime($terms->start_date))) : date('m/d/Y');
                    $universityTerms[$tkey]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                    $universityTerms[$tkey]->term_date = $universityTerms[$tkey]->start_date;
                }
                $universitiesData[$key]->terms = $universityTerms;
            }
            
            // Get Campuses(Housing) by University id ...
            $campuses = new Campuses(); 
            $campusesData = $campuses->getCampusesData($val->id);
            if(!empty($campusesData)){
                $universitiesData[$key]->housing = $campusesData;
            }
            
            // Get Program(Diplomas) by University id ...
            $diplomas = new Diplomas();
            $programData = $diplomas->getProgramData($val->id);
            if(!empty($programData)){
                $universitiesData[$key]->diplomas = $programData;
            }
            
            // Get pictures of University by University id
            $universitiesPictures = UniversitiesPictures::where('university_id', $val->id)->get();
            if(!empty($universitiesPictures)){
                $universitiesData[$key]->pictures = $universitiesPictures;
            }
            
        }
        return $universitiesData;   	
    }
    
    /**
     * Get Universities Price by semester...
     * @param $data
     * 
     * @return Mixed
     */
    public function filterUniversitiesByPriceData($data) {
      
        // Get Universities filter Price by semester ...
        $searchQuery = DB::table('universities as u')->select('u.*');
        if(!empty($data['to'])){
			$searchQuery = $searchQuery->leftJoin('diplomas as d', 'd.university_id', '=', 'u.id')
										->whereBetween('d.price',[$data['from'], $data['to']]);
		}
	    $universitiesData = $searchQuery->orderBy('u.id', 'desc')
	    								->groupBy('u.id')
	    								->get();
        
        foreach($universitiesData as $key=>$val){
            
            // Get Address by address id of university ...
            if(!empty($val->address_id)){
                $addresses = addresses::find($val->address_id);
                $universitiesData[$key]->address = $addresses;
            }
            
            // Get Reference of country by reference country id of university ...
	        if(!empty($val->ref_country_id)){
	            $refCountry = refCountry::find($val->ref_country_id);
	            $universitiesData[$key]->refCountry = $refCountry;
	        }
            
            // Get Contacts by User id of university ...
            $contacts = contacts::where('user_id', $val->user_id)->first();
            if(!empty($contacts)){
                $universitiesData[$key]->contacts = $contacts; 
            }
            
            // Get Terms by University id ...
            $universityTerms = universityTerms::where('university_id', $val->id)->get();
            $universityTermsCount =  $universityTerms->count();
            if(!empty($universityTermsCount)){
                $universitiesData[$key]->termsCount = $universityTermsCount; 
                foreach($universityTerms as $tkey=>$terms){
                    if(!empty($terms->term_type_id)){
                        $refTermType = refTermType::find($terms->term_type_id);
                        $universityTerms[$tkey]->term_name = $refTermType->name;
                    }
                    $universityTerms[$tkey]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                    $universityTerms[$tkey]->term_date = $universityTerms[$tkey]->start_date;
                }
                $universitiesData[$key]->terms = $universityTerms;
            }
            
            // Get Campuses(Housing) by University id ...
            $campuses = new Campuses(); 
            $campusesData = $campuses->getCampusesData($val->id);
            if(!empty($campusesData)){
                $universitiesData[$key]->housing = $campusesData;
            }
            
            // Get Program(Diplomas) by University id ...
            $diplomas = new Diplomas();
            $programData = $diplomas->getProgramData($val->id);
            if(!empty($programData)){
                $universitiesData[$key]->diplomas = $programData;
            }
            
            // Get pictures of University by University id
            $universitiesPictures = UniversitiesPictures::where('university_id', $val->id)->get();
            if(!empty($universitiesPictures)){
                $universitiesData[$key]->pictures = $universitiesPictures;
            }
            
        }
        return $universitiesData;   	
    }
    
     /**
     * Get Universities by Icons...
     * @param $data
     * 
     * @return Mixed
     */
    public function filterUniversitiesByIconsData($data) {
       
        // Get Universities filter Price by semester ...
        $searchQuery = DB::table('universities as u')->select('u.*');
        if(!empty($data) && $data == 'English'){
        	
			$searchQuery = $searchQuery->leftJoin('diplomas as d', 'd.university_id', '=', 'u.id')
										->leftJoin('ref_language as rl', 'rl.id', '=', 'd.ref_language_id')
										->where('rl.name',$data);
		}
	    $universitiesData = $searchQuery->orderBy('u.id', 'desc')
	    								->groupBy('u.id')
	    								->get();
        
        foreach($universitiesData as $key=>$val){
            
            // Get Address by address id of university ...
            if(!empty($val->address_id)){
                $addresses = addresses::find($val->address_id);
                $universitiesData[$key]->address = $addresses;
            }
            
            // Get Reference of country by reference country id of university ...
	        if(!empty($val->ref_country_id)){
	            $refCountry = refCountry::find($val->ref_country_id);
	            $universitiesData[$key]->refCountry = $refCountry;
	        }
            
            // Get Contacts by User id of university ...
            $contacts = contacts::where('user_id', $val->user_id)->first();
            if(!empty($contacts)){
                $universitiesData[$key]->contacts = $contacts; 
            }
            
            // Get Terms by University id ...
            $universityTerms = universityTerms::where('university_id', $val->id)->get();
            $universityTermsCount =  $universityTerms->count();
            if(!empty($universityTermsCount)){
                $universitiesData[$key]->termsCount = $universityTermsCount; 
                foreach($universityTerms as $tkey=>$terms){
                    if(!empty($terms->term_type_id)){
                        $refTermType = refTermType::find($terms->term_type_id);
                        $universityTerms[$tkey]->term_name = $refTermType->name;
                    }
                    $universityTerms[$tkey]->start_date = !empty($terms->start_date) ? date('d/m/Y', strtotime($terms->start_date)) : date('d/m/Y');
                    $universityTerms[$tkey]->term_date = $universityTerms[$tkey]->start_date;
                }
                $universitiesData[$key]->terms = $universityTerms;
            }
            
            // Get Campuses(Housing) by University id ...
            $campuses = new Campuses(); 
            $campusesData = $campuses->getCampusesData($val->id);
            if(!empty($campusesData)){
                $universitiesData[$key]->housing = $campusesData;
            }
            
            // Get Program(Diplomas) by University id ...
            $diplomas = new Diplomas();
            $programData = $diplomas->getProgramData($val->id);
            if(!empty($programData)){
                $universitiesData[$key]->diplomas = $programData;
            }
            
            // Get pictures of University by University id
            $universitiesPictures = UniversitiesPictures::where('university_id', $val->id)->get();
            if(!empty($universitiesPictures)){
                $universitiesData[$key]->pictures = $universitiesPictures;
            }
            
        }
        return $universitiesData;   	
    }
}
