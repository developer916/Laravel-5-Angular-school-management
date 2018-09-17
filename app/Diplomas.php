<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Universities;
use App\DiplomaSpecializations;
use App\DiplomaRequirements;
use App\UniversitiesDiplomaGrades;
use App\UniversitiesDiplomaGradesDiplomaRequirements;
use App\DiplomaSemesters;
use App\SemesterClasses;
//use App\DiplomaGrade;
use App\RefLanguage;
use App\DiplomaStudy;
use App\DiplomaLevelStudy;

class Diplomas extends Model
{
    protected $table = 'diplomas';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Get Diplomas by University id ...
     * 
     * @return Object
     */
    public function scopeByDiplomas($query, $university_id){
        return $query->where('university_id',$university_id);
    }
    
    
    /**
     * Get Diplomas by University id ...
     * 
     * @return Mixed
     */
    public function getProgramData($university_id)
    {
        $diplomasData = $this->byDiplomas($university_id)->get();
        foreach($diplomasData as $key=>$diploma){
            
            // Get University By University id ....
            if(!empty($university_id)){
                $universities = Universities::find($university_id);
                $diplomasData[$key]->universities = $universities;
            }
            
            // Get diploma study name ....
            if(!empty($diploma->diploma_study_id)){
                $diplomaStudy = DiplomaStudy::find($diploma->diploma_study_id);
                $diplomasData[$key]->diploma_study_name = $diplomaStudy->name;
            }
            
            // Get diploma level of study name ... 
            if(!empty($diploma->diploma_level_study_id)){
                $diplomaLevelStudy= DiplomaLevelStudy::find($diploma->diploma_level_study_id);
                $diplomasData[$key]->diploma_level_study_name = $diplomaLevelStudy->name;
            }
            
            // Get diploma reference of language name...
            if(!empty($diploma->ref_language_id)){
                $refLanguage = RefLanguage::find($diploma->ref_language_id);
                $diplomasData[$key]->language = $refLanguage->name;
            }
            
            // Get diploma Specialization details...
            if(!empty($diploma->diploma_specialization_id)){
                $diplomaSpecializations = DiplomaSpecializations::find($diploma->diploma_specialization_id);
                if(!empty($diplomaSpecializations)){
			    	$diplomasData[$key]->specializations = $diplomaSpecializations->name;
                	$diplomasData[$key]->diploma_category_id = $diplomaSpecializations->diploma_category_id;
                }
            }
            
            // Get University Diploma Grade Detail...
            if(!empty($diploma->universities_diploma_grade_id)){
                $universitiesDiplomaGrades = UniversitiesDiplomaGrades::find($diploma->universities_diploma_grade_id);
                $diplomasData[$key]->universitiesDiplomaGrades = $universitiesDiplomaGrades;
            }
            
            // Get Diploma Requirements Details...
            $diplomaRequirements = DiplomaRequirements::where('diploma_id', $diploma->id)->get();
            if(!empty($diplomaRequirements)){
                $diplomasData[$key]->diplomaRequirements = $diplomaRequirements;
            }
            
            if(!empty($diplomasData[$key]->universitiesDiplomaGrades) && !empty($diplomasData[$key]->diplomaRequirements)){
                $requirements = array();
                foreach($diplomasData[$key]->diplomaRequirements as $k=>$val){
                    $universitiesDiplomaGradesDiplomaRequirements = UniversitiesDiplomaGradesDiplomaRequirements::where(array('universities_diploma_grade_id'=>$diplomasData[$key]->universitiesDiplomaGrades->id, 'diploma_requirement_id'=>$val->id))->first();
                    if(!empty($universitiesDiplomaGradesDiplomaRequirements)){
                        $requirements[] = $universitiesDiplomaGradesDiplomaRequirements;
                    }
                }
                $diplomasData[$key]->universitiesDiplomaGradesDiplomaRequirements = $requirements;
            }
            
          	// Get Diploma Semester With class name
			$diplomaSemesters = DiplomaSemesters::where('diploma_id', $diploma->id)->get();
			if(!empty($diplomaSemesters)){
				$diplomasData->numberOfSemeter = count($diplomaSemesters);
				foreach($diplomaSemesters as $skey=>$val){
					$semesterClasses = SemesterClasses::where('diploma_semester_id', $val->id)->get();
					if(!empty($semesterClasses)){
						$diplomaSemesters[$skey]->classes = count($semesterClasses);
						$diplomaSemesters[$skey]->semesterClasses = $semesterClasses;
					}
				}				
				$diplomasData[$key]->diplomaSemesters = $diplomaSemesters;
			}
            
        }
        return $diplomasData;
    }
    
    /**
     * Get Diploma by Diploma id ...
     * 
     * @return Mixed
     */
    public function getDiplomaByIdData($diploma_id)
    {
        $diplomaData = $this->find($diploma_id);
       	if(!empty($diplomaData)){
	        
	        //Explode name of diploma
	        if(!empty($diplomaData->name)){
				$name = explode(' in ', $diplomaData->name);
				$diplomaData->nameBest = !empty($name[0])? $name[0]:'';
				$diplomaData->nameIn = !empty($name[1])? $name[1]:'';
			}
	        
            // Get University By University id ....
            if(!empty($diplomaData->university_id)){
                $universities = Universities::find($diplomaData->university_id);
                $diplomaData->universities = $universities;
            }
            
            // Get diploma study name ....
            if(!empty($diplomaData->diploma_study_id)){
                $diplomaStudy = DiplomaStudy::find($diplomaData->diploma_study_id);
                $diplomaData->diploma_study_name = $diplomaStudy->name;
            }
            
            // Get diploma level of study name ... 
            if(!empty($diplomaData->diploma_level_study_id)){
                $diplomaLevelStudy= DiplomaLevelStudy::find($diplomaData->diploma_level_study_id);
                $diplomaData->diploma_level_study_name = $diplomaLevelStudy->name;
            }
            
            // Get diploma reference of language name...
            if(!empty($diplomaData->ref_language_id)){
                $refLanguage = RefLanguage::find($diplomaData->ref_language_id);
                $diplomaData->language = $refLanguage->name;
            }
            
            // Get diploma Specialization details...
            if(!empty($diplomaData->diploma_specialization_id)){
                $diplomaSpecializations = DiplomaSpecializations::find($diplomaData->diploma_specialization_id);
                if(!empty($diplomaSpecializations)){
			    	$diplomaData->specialization = $diplomaSpecializations->name;
                	$diplomaData->diploma_category_id = $diplomaSpecializations->diploma_category_id;
                }
            }
            
            // Get University Diploma Grade Detail...
            if(!empty($diplomaData->universities_diploma_grade_id)){
                $universitiesDiplomaGrades = UniversitiesDiplomaGrades::find($diplomaData->universities_diploma_grade_id);
                $diplomaData->universitiesDiplomaGrades = $universitiesDiplomaGrades;
            }
            
            // Get Diploma Requirements Details...
            $diplomaRequirements = DiplomaRequirements::where('diploma_id', $diplomaData->id)->get();
            if(!empty($diplomaRequirements)){
                $diplomaData->diplomaRequirements = $diplomaRequirements;
            }
            
            if(!empty($diplomaData->universitiesDiplomaGrades) && !empty($diplomaData->diplomaRequirements)){
                
                $requirements = array();
                foreach($diplomaData->diplomaRequirements as $k=>$val){
                    $universitiesDiplomaGradesDiplomaRequirements = UniversitiesDiplomaGradesDiplomaRequirements::where(array('universities_diploma_grade_id'=>$diplomaData->universitiesDiplomaGrades->id, 'diploma_requirement_id'=>$val->id))->first();
                    if(!empty($universitiesDiplomaGradesDiplomaRequirements)){
                        $requirements[] = $universitiesDiplomaGradesDiplomaRequirements;
                    }
                }
                $diplomaData->universitiesDiplomaGradesDiplomaRequirements = $requirements;
            }
            
            // Get Diploma Semester With class name
			$diplomaSemesters = DiplomaSemesters::where('diploma_id', $diplomaData->id)->get();
			if(!empty($diplomaSemesters)){
				$diplomaData->numberOfSemeter = count($diplomaSemesters);
				foreach($diplomaSemesters as $skey=>$val){
					$semesterClasses = SemesterClasses::where('diploma_semester_id', $val->id)->get();
					if(!empty($semesterClasses)){
						$diplomaSemesters[$skey]->classes = count($semesterClasses);
						$diplomaSemesters[$skey]->semesterClasses = $semesterClasses;
					}
				}				
				$diplomaData->diplomaSemesters = $diplomaSemesters;
			}
            
        }
        return $diplomaData;
    }
    
    /**
     * Get All Diplomas ...
     * 
     * @return Mixed
     */
    public function getAllProgramData()
    {
    	// Get all Diploma all method
        $diplomasData = $this->all();
        foreach($diplomasData as $key=>$diploma){
            
            // Get University By University id ...
            if(!empty($diploma->university_id)){
                $universities = Universities::find($diploma->university_id);
                $diplomasData[$key]->universities = $universities;
            }
            
            // Get diploma study name ....
            if(!empty($diploma->diploma_study_id)){
                $diplomaStudy = DiplomaStudy::find($diploma->diploma_study_id);
                $diplomasData[$key]->diploma_study_name = $diplomaStudy->name;
            }
            
            // Get diploma level of study name ... 
            if(!empty($diploma->diploma_level_study_id)){
                $diplomaLevelStudy= DiplomaLevelStudy::find($diploma->diploma_level_study_id);
                $diplomasData[$key]->diploma_level_study_name = $diplomaLevelStudy->name;
            }
            
            // Get diploma reference of language name...
            if(!empty($diploma->ref_language_id)){
                $refLanguage = RefLanguage::find($diploma->ref_language_id);
                $diplomasData[$key]->language = $refLanguage->name;
            }
            
            // Get diploma Specialization details...
            if(!empty($diploma->diploma_specialization_id)){
                $diplomaSpecializations = DiplomaSpecializations::find($diploma->diploma_specialization_id);
                if(!empty($diplomaSpecializations->name)){
					$diplomasData[$key]->specializations = $diplomaSpecializations->name;
                	$diplomasData[$key]->diploma_category_id = $diplomaSpecializations->diploma_category_id;				}
                
            }
            
            // Get University Diploma Grade Detail...
            if(!empty($diploma->universities_diploma_grade_id)){
                $universitiesDiplomaGrades = UniversitiesDiplomaGrades::find($diploma->universities_diploma_grade_id);
                $diplomasData[$key]->universitiesDiplomaGrades = $universitiesDiplomaGrades;
            }
                  
            // Get Diploma Requirements document list..
            $diplomaRequirements = DiplomaRequirements::where('diploma_id', $diploma->id)->get();
            if(!empty($diplomaRequirements)){
                $diplomasData[$key]->diplomaRequirements = $diplomaRequirements;
            }
            if(!empty($diplomasData[$key]->universitiesDiplomaGrades) && !empty($diplomasData[$key]->diplomaRequirements)){
                $requirements = array();
                foreach($diplomasData[$key]->diplomaRequirements as $k=>$val){
                    $universitiesDiplomaGradesDiplomaRequirements = UniversitiesDiplomaGradesDiplomaRequirements::where(array('universities_diploma_grade_id'=>$diplomasData[$key]->universitiesDiplomaGrades->id, 'diploma_requirement_id'=>$val->id))->first();
                    if(!empty($universitiesDiplomaGradesDiplomaRequirements)){
                        $requirements[] = $universitiesDiplomaGradesDiplomaRequirements;
                    }
                }
                $diplomasData[$key]->universitiesDiplomaGradesDiplomaRequirements = $requirements;
            }
            
			// Get Diploma Semester With class name
			$diplomaSemesters = DiplomaSemesters::where('diploma_id', $diploma->id)->get();
			if(!empty($diplomaSemesters)){
				$diplomasData->numberOfSemeter = count($diplomaSemesters);
				foreach($diplomaSemesters as $skey=>$val){
					$semesterClasses = SemesterClasses::where('diploma_semester_id', $val->id)->get();
					if(!empty($semesterClasses)){
						$diplomaSemesters[$skey]->classes = count($semesterClasses);
						$diplomaSemesters[$skey]->semesterClasses = $semesterClasses;
					}
				}				
				$diplomasData[$key]->diplomaSemesters = $diplomaSemesters;
			}
	        
        }
        return $diplomasData;
    }
    
    
    /**
     * Save Diplomas By University id...
     * 
     * @return Mixed
     */
    public function saveProgramData($data, $university_id) {
       
        // Update Program Details...	
        if(!empty($data['id'])){
        	//Get diploma Details For update data...
        	$diplomaData = $this->find($data['id']);
        	
        	// Update Diploma Specialization
        	if(!empty($diplomaData->diploma_specialization_id)){
        	    $diplomaSpecializations = DiplomaSpecializations::find($diplomaData->diploma_specialization_id);
	            $diplomaSpecializations->name = !empty($data['specialization'])? $data['specialization'] : '';
	            $diplomaSpecializations->save();
			}else{
			    $diplomaSpecializations = new DiplomaSpecializations;
	            $diplomaSpecializations->name = !empty($data['specialization'])? $data['specialization'] : '';
	            $diplomaSpecializations->save();
	       	}
            
            // Update Universities Diploma Grades
            if(!empty($diplomaData->universities_diploma_grade_id)){					    
	            $universitiesDiplomaGrades = UniversitiesDiplomaGrades::find($diplomaData->universities_diploma_grade_id);
	            //$universitiesDiplomaGrades->diploma_grade_offered_id = 0;
	            //$universitiesDiplomaGrades->diploma_grade_min_level_id = 0;
	            $universitiesDiplomaGrades->ref_language_id = !empty($data['ref_language'])? $data['ref_language'] : '';
	            $universitiesDiplomaGrades->save();
			}else{
			    $universitiesDiplomaGrades= new UniversitiesDiplomaGrades;
	            $universitiesDiplomaGrades->university_id = $diplomaData->university_id;
	            $universitiesDiplomaGrades->diploma_grade_offered_id = 0;
	            $universitiesDiplomaGrades->diploma_grade_min_level_id = 0;
	            $universitiesDiplomaGrades->ref_language_id = !empty($data['ref_language'])? $data['ref_language'] : '';
	            $universitiesDiplomaGrades->save();
	       	}
           
            // update Diploma Details...
            $nameBest = !empty($data['nameBest'])? $data['nameBest'] : '';
            $nameIn = !empty($data['nameIn'])? $data['nameIn'] : '';
            
            $diplomaData->diploma_study_id              = !empty($data['diploma_study_id'])? $data['diploma_study_id'] : '';
            $diplomaData->diploma_level_study_id		= !empty($data['level_of_study'])? $data['level_of_study'] : '';
            $diplomaData->diploma_grade_id 	         = 0;
            $diplomaData->ref_language_id 	         = !empty($data['ref_language'])? $data['ref_language'] : '';
            $diplomaData->diploma_specialization_id 	 = $diplomaSpecializations->id;
            $diplomaData->diploma_grade_required_id     = 0;
            $diplomaData->universities_diploma_grade_id = !empty($universitiesDiplomaGrades->id)? $universitiesDiplomaGrades->id : 0;
            $diplomaData->name 	                 = $nameBest.' in '.$nameIn;
            $diplomaData->description 	                 = !empty($data['description'])? $data['description'] : '';
            $diplomaData->price                         = !empty($data['price'])? $data['price'] : '';
            $diplomaData->save();
            
            // Update Diploma Requirements Documents ...
            if(!empty($data['otherDocuments'])){
                foreach($data['otherDocuments'] as $val){
                    if(!empty($val['name'])){
                    	if(!empty($val['id'])){
                    		$diplomaRequirements = DiplomaRequirements::find($val['id']);
	                        $diplomaRequirements->document_required = !empty($data['document'])? $data['document'] : '';
	                        $diplomaRequirements->name = $val['name'];
	                        $diplomaRequirements->save();
	                         
	                        UniversitiesDiplomaGradesDiplomaRequirements::where('diploma_requirement_id', $diplomaRequirements->id)->update([ 
	                        'universities_diploma_grade_id' => !empty($universitiesDiplomaGrades->id) ? $universitiesDiplomaGrades->id : 0,
                            ]);
	                        
						}else{		
	                        $diplomaRequirements = new DiplomaRequirements;
	                        $diplomaRequirements->diploma_id = $diplomaData->id;
	                        $diplomaRequirements->document_required = !empty($data['document'])? $data['document'] : '';
	                        $diplomaRequirements->name = $val['name'];
	                        $diplomaRequirements->save();
	                        
	                        $universitiesDiplomaGradesDiplomaRequirements = new UniversitiesDiplomaGradesDiplomaRequirements;
	                        $universitiesDiplomaGradesDiplomaRequirements->universities_diploma_grade_id = !empty($universitiesDiplomaGrades->id) ? $universitiesDiplomaGrades->id : 0;
	                        $universitiesDiplomaGradesDiplomaRequirements->diploma_requirement_id = !empty($diplomaRequirements->id)? $diplomaRequirements->id : 0;
	                        $universitiesDiplomaGradesDiplomaRequirements->save();
                        }
                    }
                }
            }
            
            // Update Diploma Number of Semester exist or not...
            if(!empty($data['numberOfSemeter']) && !empty($data['semester'])){
				foreach($data['semester'] as $key=>$val){
					if(!empty($val['id'])){
						// Update Number of Semester...
						$diplomaSemesters = DiplomaSemesters::find($val['id']);
			            $diplomaSemesters->diploma_id = $diplomaData->id;
			            $diplomaSemesters->orders = $val['classes'];
			            $diplomaSemesters->save();		
			            
						// Update Semester of Classes with name...
						if(!empty($val['classes']) && !empty($val['semesterClasses'])){
							foreach($val['semesterClasses'] as $classVal){
								if(!empty($classVal['name'])){
									if(!empty($classVal['id'])){
										$semesterClasses = SemesterClasses::find($classVal['id']);
							            $semesterClasses->name = $classVal['name'];
							            $semesterClasses->save();
									}else{
										$semesterClasses = new SemesterClasses;
							            $semesterClasses->diploma_semester_id = $diplomaSemesters->id;
							            $semesterClasses->name = $classVal['name'];
							            $semesterClasses->save();	
									}
						        }
				            }
			            }
					}else{
						// Save Number of Semester...
						$diplomaSemesters = new DiplomaSemesters;
			            $diplomaSemesters->diploma_id = $diplomaData->id;
			            $diplomaSemesters->orders = $val['classes'];
			            $diplomaSemesters->save();		
			            
						// Save Semester of Classes with name...
						if(!empty($val['classes']) && !empty($val['semesterClasses'])){
							foreach($val['semesterClasses'] as $classVal){
								if(!empty($classVal['name'])){
									$semesterClasses = new SemesterClasses;
						            $semesterClasses->diploma_semester_id = $diplomaSemesters->id;
						            $semesterClasses->name = $classVal['name'];
						            $semesterClasses->save();
					            }
				            }
			            }
		            }
				}
			}
			return $diplomaData->id;
            
		}else{
			
        	// Save Diploma Specialization
            $diplomaSpecializations = new DiplomaSpecializations;
            $diplomaSpecializations->name = !empty($data['specialization'])? $data['specialization'] : '';
            $diplomaSpecializations->save();
            
            // Save Universities Diploma Grades
            $universitiesDiplomaGrades= new UniversitiesDiplomaGrades;
            $universitiesDiplomaGrades->university_id = $university_id;
            $universitiesDiplomaGrades->diploma_grade_offered_id = 0;
            $universitiesDiplomaGrades->diploma_grade_min_level_id = 0;
            $universitiesDiplomaGrades->ref_language_id = !empty($data['ref_language'])? $data['ref_language'] : '';
            $universitiesDiplomaGrades->save();
           
            // Save Diploma Details...
            $nameBest = !empty($data['nameBest'])? $data['nameBest'] : '';
            $nameIn = !empty($data['nameIn'])? $data['nameIn'] : '';
            
            $this->university_id 	         = $university_id;
            $this->diploma_study_id              = !empty($data['diploma_study_id'])? $data['diploma_study_id'] : '';
            $this->diploma_level_study_id		= !empty($data['level_of_study'])? $data['level_of_study'] : '';
            $this->diploma_grade_id 	         = 0;
            $this->ref_language_id 	         = !empty($data['ref_language'])? $data['ref_language'] : '';
            $this->diploma_specialization_id 	 = $diplomaSpecializations->id;
            $this->diploma_grade_required_id     = 0;
            $this->universities_diploma_grade_id = !empty($universitiesDiplomaGrades->id)? $universitiesDiplomaGrades->id : 0;
            $this->name 	                 = $nameBest.' in '.$nameIn;
            $this->description 	                 = !empty($data['description'])? $data['description'] : '';
            $this->price                         = !empty($data['price'])? $data['price'] : '';
            $this->save();
            $diploma_id = $this->id;
            
            // Save Diploma Requirements Documents ...
            if(!empty($data['otherDocuments'])){
                foreach($data['otherDocuments'] as $val){
                    if(!empty($val['name'])){
                        $diplomaRequirements = new DiplomaRequirements;
                        $diplomaRequirements->diploma_id = $diploma_id;
                        $diplomaRequirements->document_required = !empty($data['document'])? $data['document'] : '';
                        $diplomaRequirements->name = $val['name'];
                        $diplomaRequirements->save();
                        
                        $universitiesDiplomaGradesDiplomaRequirements = new UniversitiesDiplomaGradesDiplomaRequirements;
                        $universitiesDiplomaGradesDiplomaRequirements->universities_diploma_grade_id = !empty($universitiesDiplomaGrades->id) ? $universitiesDiplomaGrades->id : 0;
                        $universitiesDiplomaGradesDiplomaRequirements->diploma_requirement_id = !empty($diplomaRequirements->id)? $diplomaRequirements->id : 0;
                        $universitiesDiplomaGradesDiplomaRequirements->save();
                    }
                }
            }
            
            // Check to Number of Semester exist or not...
            if(!empty($data['numberOfSemeter']) && !empty($data['semester'])){
				foreach($data['semester'] as $key=>$val){
					// Save Number of Semester...
					$diplomaSemesters = new DiplomaSemesters;
		            $diplomaSemesters->diploma_id = $diploma_id;
		            $diplomaSemesters->orders = $val['classes'];
		            $diplomaSemesters->save();		
		            
					// Save Semester of Classes with name...
					if(!empty($val['classes']) && !empty($val['semesterClasses'])){
						foreach($val['semesterClasses'] as $classVal){
							if(!empty($classVal['name'])){
								$semesterClasses = new SemesterClasses;
					            $semesterClasses->diploma_semester_id = $diplomaSemesters->id;
					            $semesterClasses->name = $classVal['name'];
					            $semesterClasses->save();
				            }
			            }
		            }
				}
			}
            
            return $diploma_id;
		}	   	
    }
    
    /**
     * Copy Diploma by Diploma id ...
     * @purpose Copy record and duplicate with new values
     * @param 
     * 
     * @return Mixed 
     */
    public function copyProgramData($diploma_id)
    {
    	//Get Diploma By diploma id ...
        $diplomaData = $this->find($diploma_id);
        if(!empty($diplomaData)){
	    	
        	// Copy Diploma Specialization
            if(!empty($diplomaData->diploma_specialization_id)){
			    $diplomaSpecializations = DiplomaSpecializations::find($diplomaData->diploma_specialization_id);
	            $copyDiplomaSpecializations = $diplomaSpecializations->replicate();
	            $copyDiplomaSpecializations->save();
			}
            
            // Copy Universities Diploma Grades
            if(!empty($diplomaData->universities_diploma_grade_id)){
	            $universitiesDiplomaGrades = UniversitiesDiplomaGrades::find($diplomaData->universities_diploma_grade_id);
	            $copyUniversitiesDiplomaGrades = $universitiesDiplomaGrades->replicate();
	            $copyUniversitiesDiplomaGrades->save();
			}
        
        	// Copy Diploma data
        	$copyDiplomaData = $diplomaData->replicate();
        	$copyDiplomaData->diploma_specialization_id = !empty($copyDiplomaSpecializations->id)? $copyDiplomaSpecializations->id : 0;
            $copyDiplomaData->universities_diploma_grade_id = !empty($copyUniversitiesDiplomaGrades->id)? $copyUniversitiesDiplomaGrades->id : 0;
	        $copyDiplomaData->save();
	       
	       	
            if(!empty($copyDiplomaData->id)){
            	
            	// Copy Diploma Requirements Documents ...
            	$diplomaRequirements = DiplomaRequirements::where('diploma_id', $diplomaData->id)->get();
            	if(!empty($diplomaRequirements)){
				    foreach($diplomaRequirements as $val){
	                    if(!empty($val->name)){
	                        $copyDiplomaRequirements = new DiplomaRequirements;
	                        $copyDiplomaRequirements->diploma_id = $copyDiplomaData->id;
	                        $copyDiplomaRequirements->document_required = !empty($val->document_required)? $val->document_required : '';
	                        $copyDiplomaRequirements->name = $val->name;
	                        $copyDiplomaRequirements->save();
	                        
	                        $universitiesDiplomaGradesDiplomaRequirements = new UniversitiesDiplomaGradesDiplomaRequirements;
	                        $universitiesDiplomaGradesDiplomaRequirements->universities_diploma_grade_id = !empty($copyUniversitiesDiplomaGrades->id) ? $copyUniversitiesDiplomaGrades->id : 0;
	                        $universitiesDiplomaGradesDiplomaRequirements->diploma_requirement_id = !empty($copyDiplomaRequirements->id)? $copyDiplomaRequirements->id : 0;
	                        $universitiesDiplomaGradesDiplomaRequirements->save();
	                    }
	                }
				}
				
				// Check to Number of Semester exist or not...
				$diplomaSemesters = DiplomaSemesters::where('diploma_id', $diplomaData->id)->get();
	            if(!empty($diplomaSemesters)){
					foreach($diplomaSemesters as $key=>$val){
						// Copy Number of Semester...
						$copyDiplomaSemesters = new DiplomaSemesters;
			            $copyDiplomaSemesters->diploma_id = $copyDiplomaData->id;
			            $copyDiplomaSemesters->orders = !empty($val->orders) ? $val->orders : 0;
			            $copyDiplomaSemesters->save();		
			            
						// Copy Semester of Classes with name...
						$semesterClasses = SemesterClasses::where('diploma_semester_id', $val->id)->get();
						if(!empty($semesterClasses)){
							foreach($semesterClasses as $classVal){
								if(!empty($classVal->name)){
									$copySemesterClasses = new SemesterClasses;
						            $copySemesterClasses->diploma_semester_id = $copyDiplomaSemesters->id;
						            $copySemesterClasses->name = $classVal->name;
						            $copySemesterClasses->save();
					            }
				            }
			            }
					}
				}
		    }
		    return $copyDiplomaData->id;
	   	}
    }  
    
    /**
     * Delete Diploma by Diploma id ...
     * @purpose Delete record of Diploma 
     * @param $diploma_id
     * 
     * @return true or false
     */
    public function deleteProgramData($diploma_id)
    {
    	//Get Diploma By diploma id ...
        $diplomaData = $this->find($diploma_id);
        if(!empty($diplomaData)){
        	
        	//Delete DiplomaSpecializations Records
        	if(!empty($diplomaData->diploma_specialization_id)){
        	    DiplomaSpecializations::destroy($diplomaData->diploma_specialization_id);
			}
			    
        	//Delete UniversitiesDiplomaGrades Records
        	if(!empty($diplomaData->universities_diploma_grade_id)){
	          	UniversitiesDiplomaGrades::destroy($diplomaData->universities_diploma_grade_id);
			}
			
			//Delete Diploma Records
			$this->destroy($diploma_id);
			
			//Delete DiplomaRequirements Records
			$diplomaRequirements = DiplomaRequirements::where('diploma_id', $diploma_id)->get();
        	if(!empty($diplomaRequirements)){
			    foreach($diplomaRequirements as $val){
					UniversitiesDiplomaGradesDiplomaRequirements::where('diploma_requirement_id', $val->id)->delete();
    			}
			}
			DiplomaRequirements::where('diploma_id', $diploma_id)->delete();
			
			//Delete DiplomaSemesters Records
			$diplomaSemesters = DiplomaSemesters::where('diploma_id', $diploma_id)->get();
            if(!empty($diplomaSemesters)){
				foreach($diplomaSemesters as $key=>$val){
					SemesterClasses::where('diploma_semester_id', $val->id)->delete();
				}
			}
			DiplomaSemesters::where('diploma_id', $diploma_id)->delete();
			
			return true;
		}else{
			return false;
		}
	}
    
    /**
     * Get Diploma by Diploma id ...
     * @purpose single diploma data Using into application to school user for check to diploma required document list
     * 
     * @return Mixed 
     */
    public function getProgramByIdData($diploma_id)
    {
    	//Get Diploma By diploma id ...
        $diplomaData = $this->find($diploma_id);
        
        // Get Requirements Details..
        $diplomaRequirements = DiplomaRequirements::where('diploma_id', $diploma_id)->get();
        if(!empty($diplomaRequirements)){
            $diplomaData->diplomaRequirements = $diplomaRequirements;
        }
        if(!empty($diplomaData->diplomaRequirements)){
            $requirements = array();
            foreach($diplomaData->diplomaRequirements as $k=>$val){
                $universitiesDiplomaGradesDiplomaRequirements = UniversitiesDiplomaGradesDiplomaRequirements::where(array('diploma_requirement_id'=>$val->id))->first();
                if(!empty($universitiesDiplomaGradesDiplomaRequirements)){
                    $requirements[] = $universitiesDiplomaGradesDiplomaRequirements;
                }
            }
            $diplomaData->universitiesDiplomaGradesDiplomaRequirements = $requirements;
        }
        return $diplomaData;
    }
    
    /**
     * Get All Speciality of Diplomas ...
     * 
     * @return Mixed
     */
    public function getAllSpecialityData()
    {
    	$diplomaSpecializations = DiplomaSpecializations::all();
    	//if(!empty($diplomaSpecializations->diploma_category_id)){}
    	return $diplomaSpecializations;
	}
}
