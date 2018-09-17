<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\refCountry;
use Auth;

class StudentEducations extends Model
{
    protected $table = 'student_educations';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Save Education Detail by current user login ....
     * @param $data
     * 
     * @return Education id
     */
    public function saveEducationData($profile) {
        if(!empty($profile['id'])){
            $education =  $this->find($profile['id']);
            if(!empty($profile['school']))
                $education->school 	        = $profile['school'];
            if(!empty($profile['ref_country_id']))    
                $education->ref_country_id  = $profile['ref_country_id'];
            if(!empty($profile['programOption']))
                $education->program     	= $profile['programOption'] == 'Other' ? $profile['program'] : $profile['programOption'];
            //if(!empty($profile['program']))
                //$education->program         = $profile['program'];
            if(!empty($profile['period']))    
                $education->period          = json_encode($profile['period']);
            if(!empty($profile['graduated']))
                $education->graduated 	    = $profile['graduated'];
            $education->save();
            return $education->id;
        }else{
            $user_id = Auth::user()->id; 
            $this->student_id       = Auth::user()->id; 
            $this->school 	    	= !empty($profile['school'])? $profile['school'] : '';
            $this->ref_country_id   = !empty($profile['ref_country_id'])? $profile['ref_country_id'] : '';
            //$this->program 	    = !empty($profile['program'])? $profile['program'] : '';
            $this->program 	    	= $profile['programOption'] == 'Other' ? $profile['program'] : $profile['programOption'];
            $this->period 	    	= !empty($profile['period'])? json_encode($profile['period']) : '';
            $this->graduated 	    = !empty($profile['graduated'])? $profile['graduated'] : '';
            $this->save();
            return $this->id;
        }
    }
    
    /**
     * @param Current User id or education id
     * 
     * @return Education Array
     */
    public function getEducationsData($student_id = 0){
        
        $user_id = $student_id ? $student_id : Auth::user()->id;
        $educations =  $this->where('student_id', $user_id)->get();
        foreach($educations as $key=>$val){
            if($val->ref_country_id)
				$educations[$key]->refCountry = refCountry::find($val->ref_country_id);			
            if($val->period)
				$educations[$key]->period = json_decode($val->period);	
        }
        return $educations;   
    }  
    
    /**
     * @param education id
     * 
     * @return Education Array
     */
    public function getEducationById($education_id){
        
        if($education_id){
            $education = $this->find($education_id);
            if($education->ref_country_id)
                $education->refCountry = refCountry::find($education->ref_country_id);
            if($education->period)
				$education->period = json_decode($education->period);
		    return $education;
        }
    }   
   
    /**
     * @return  Illuminate\Database\Eloquent\Relations\BelongsTo
     */  
    //public function refCountry(){
    //    return $this->belongsTo('App\refCountry', 'ref_country_id');
    //}
    
}
