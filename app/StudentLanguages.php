<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class StudentLanguages extends Model
{
    protected $table = 'student_languages';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Save Language Detail by current user login ....
     * @param $data
     * 
     * @return Language id
     */
    public function saveLanguageData($profile) {
        if(!empty($profile['id'])){
            $language =  $this->find($profile['id']);
            if(!empty($profile['name']))
                $language->name 	    = $profile['name'];
            if(!empty($profile['proficiency']))    
                $language->proficiency    = $profile['proficiency'];
            if(!empty($profile['accreditation']))
                $language->accreditation  = $profile['accreditation'];
            $language->save();
            return $language->id;
        }else{
            $user_id = Auth::user()->id; 
            $this->student_id       = Auth::user()->id; 
            $this->name 	        = !empty($profile['name'])? $profile['name'] : '';
            $this->proficiency      = !empty($profile['proficiency'])? $profile['proficiency'] : '';
            $this->accreditation    = !empty($profile['accreditation'])? $profile['accreditation'] : '';
            $this->save();
            return $this->id;
        }
    }
    
    /**
     * @param Current User id or Student Id
     * 
     * @return Language Array
     */
    public function getLanguagesData($student_id = 0){
        $user_id = $student_id ? $student_id : Auth::user()->id;
        return  $this->where('student_id', $user_id)->get();
      
    }
    
    /**
     * @param language id
     * 
     * @return Language Array
     */
    public function getLanguageById($language_id = 0){
        if($language_id){
            $language = $this->find($language_id);
            return $language;
        }
    }
    
}
