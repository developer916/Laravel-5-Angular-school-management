<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StudentExperiences extends Model
{
    protected $table = 'student_experiences';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Save Experience Detail by current user login ....
     * @param $data
     * 
     * @return Experience id
     */
    public function saveExperienceData($profile) {
        if(!empty($profile['id'])){
            $experience =  $this->find($profile['id']);
            if(!empty($profile['name']))
                $experience->name 	    = $profile['name'];
            if(!empty($profile['place']))    
                $experience->place          = $profile['place'];
            if(!empty($profile['period']))
                $experience->period         = $profile['period'];
            if(!empty($profile['type']))    
                $experience->type           = $profile['type'];
            if(!empty($profile['position']))
                $experience->position 	    = $profile['position'];
            if(!empty($profile['description']))
                $experience->description    = $profile['description'];
            $experience->save();
            return $experience->id;
        }else{
            $user_id = Auth::user()->id; 
            $this->student_id       = Auth::user()->id; 
            $this->name 	        = !empty($profile['name'])? $profile['name'] : '';
            $this->place            = !empty($profile['place'])? $profile['place'] : '';
            $this->period 	        = !empty($profile['period'])? $profile['period'] : '';
            $this->type 		= !empty($profile['type'])? $profile['type'] : '';
            $this->position 	= !empty($profile['position'])? $profile['position'] : '';
            $this->description 	= !empty($profile['description'])? $profile['description'] : '';
            $this->save();
            return $this->id;
        }
    }
    
    /**
     * @param Current User id or student id
     * 
     * @return Experience Array
     */
    public function getExperiencesData($student_id = 0){
        
        $user_id = $student_id ? $student_id : Auth::user()->id;
        return  $this->where('student_id', $user_id)->get();
        
    }
    
    /**
     * @param experience id
     * 
     * @return Experience Array
     */
    public function getExperienceById($experience_id){
        if($experience_id){
            $experience = $this->find($experience_id);
            return $experience;
        }
    }
    
}
