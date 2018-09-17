<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\ApplicationsApplicationStatuses;
use App\ApplicationsStatuses;
use App\ApplicationDocuments;
use App\StudentDocuments;
use App\DocumentTypes;
use App\DiplomaRequirements;
use App\Universities;
use App\Diplomas;
use App\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Applications extends Model
{
    protected $table = 'applications';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    /**
     * Save Application by current user login of student user....
     * @param $data
     * 
     * @return application id
     */
    public function SaveApplication($data) {
        $user_id = Auth::user()->id; 
        if(!empty($data['terms'])){
            $applicationsStatuses = ApplicationsStatuses::where('name', 'Send')->first();
            //foreach($data['terms'] as $term_id =>$val){
                //if($val == true){
                    //$existApplication = Applications::where(array('diploma_id'=> $data['diploma'], 'student_id'=> $user_id, 'university_id'=> $data['university_id'], 'university_term_id' => $data['terms']))->first();
                    $existApplication = Applications::where(array('student_id'=> $user_id, 'university_id'=> $data['university_id']))->first();
                    if(empty($existApplication->id)){
                        $applications  =  new Applications();
                        $applications->diploma_id           = !empty($data['diploma'])? $data['diploma'] : '';
                        $applications->student_id           = $user_id; 
                        $applications->university_id        = !empty($data['university_id'])? $data['university_id'] : '';
                        $applications->university_term_id   = !empty($data['terms'])? $data['terms'] : '';
                        $applications->message 	            = !empty($data['message'])? $data['message'] : '';
                        $applications->required_document    = '';
                        $applications->save();
                        
                        //Set application statuses...
                        $applicationsApplicationStatuses  =  new ApplicationsApplicationStatuses();
                        $applicationsApplicationStatuses->application_id = $applications->id;
                        $applicationsApplicationStatuses->application_status_id = $applicationsStatuses->id;
                        $applicationsApplicationStatuses->save();
                        
                        if(!empty($data['uploadDocuments'][$data['diploma']])){
                            foreach($data['uploadDocuments'][$data['diploma']] as $diploma_requirement_id=>$val){
                                if(!empty($val)){
                                    // Get Diploma Requirements Document by diploma_requirement_id  ...
                                    $diplomaRequirements  = DiplomaRequirements::find($diploma_requirement_id);
                                    
                                    // Get Selected document type id .... 
                                    $document_type_id = (integer)(!empty($data['document_types'][$data['diploma']][$diploma_requirement_id]))? $data['document_types'][$data['diploma']][$diploma_requirement_id] : 0;
                                    
                                    // Insert student application document
                                    $studentDocuments = new StudentDocuments();
                                    $studentDocuments->student_id = $user_id;
                                    $studentDocuments->document_type_id = $document_type_id;
                                    $studentDocuments->name = $diplomaRequirements->name;
                                    $studentDocuments->link = $val;
                                    $studentDocuments->save();
                                    
                                    // Insert application document references 
                                    $applicationDocuments = new ApplicationDocuments();
                                    $applicationDocuments->application_id = $applications->id;
                                    $applicationDocuments->student_document_id = $studentDocuments->id;
                                    $applicationDocuments->diploma_requirement_id = $diploma_requirement_id;
                                    $applicationDocuments->save();
                                }
                            }
                        }
                        // Send Message of application ....
                        //$university =  Universities::find($data['university_id']);
                        //if(!empty($data['message']) && !empty($university)){
                        //    $message = new Message();
                        //    $message->conversation_id = 0;
                        //    $message->user_sender_id = $user_id;
                        //    $message->user_receiver_id = $university->user_id;
                        //    $message->text = $data['message'];
                        //    $message->save(); 
                        //}
                    }else{
                        return false;
                    }
                //}
            //}
            return true;
        }
    }
    
    /**
     * Save Application by current user login of student....
     * @param $application_id, $status, $document = array()
     * 
     * @return application id
     */
    public function updateApplicationStatus($application_id, $status, $document = array()) {
        
        if(!empty($application_id) && !empty($status) && Auth::user()->type == 'school'){
            if(!empty($document)){
                $this->where('id', $application_id)->update(['required_document' => serialize($document)]);  
            }
            $applicationsStatuses = ApplicationsStatuses::where('name', $status)->first();
            if(!empty($applicationsStatuses)){
                //Set application statuses...
                $applicationsApplicationStatuses  =  new ApplicationsApplicationStatuses();
                $applicationsApplicationStatuses->application_id = $application_id;
                $applicationsApplicationStatuses->application_status_id = $applicationsStatuses->id;
                $applicationsApplicationStatuses->save();
            }
            return true;
        }elseif(!empty($application_id) && !empty($status) && Auth::user()->type == 'student'){
            $applicationsStatuses = ApplicationsStatuses::where('name', $status)->first();
            if(!empty($applicationsStatuses)){
                //Set application statuses...
                $applicationsApplicationStatuses  =  new ApplicationsApplicationStatuses();
                $applicationsApplicationStatuses->application_id = $application_id;
                $applicationsApplicationStatuses->application_status_id = $applicationsStatuses->id;
                $applicationsApplicationStatuses->save();
            }
            return true;
        }
    }
    
    /**
     * Get Applications by Current user id
     * @param Current User id
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsData(){
        $user_id = Auth::user()->id;
        if(Auth::user()->type == 'school'){
            $university = Universities::byUser($user_id)->first();
            $applications = $this->where('university_id', $university->id)->orderBy('id', 'desc')->get();
            foreach($applications as $key=>$val){
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id))
                        $applications[$key]->application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                }
                // Get University array by university id
                //$universities = new Universities();
                $applications[$key]->university = $university;
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
                
                //Get Student array by student id ...
                $student = User::getUserAccountById($val->student_id);
                $applications[$key]->student = $student;
            }
        }else{
            $applications = $this->where('student_id', $user_id)->orderBy('id', 'desc')->get();
            foreach($applications as $key=>$val){
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id))
                        $applications[$key]->application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                }
                // Get University array by university id
                $universities = new Universities();
                $applications[$key]->university = $universities->getUniversityByIdData($val->university_id);
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
            }
        }
        return $applications;
    }
    
    /**
     * Get Applications by Current user id With Sort By Update status
     * @param Current User id
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsSortByStatusData($status){
        $sortby = array();
        
        //if($status == 'Open'){
        //    $sortby = 'Review';
        //}elseif($status == 'Pending'){
        //    $sortby = 'send';
        //}elseif($status == 'Accepted'){
        //    $sortby = 'Accepted';
        //}elseif($status == 'Rejected'){
        //    $sortby = 'Rejected';
        //}
        if($status == 'Open'){
            $sortby = array('Review');
        }elseif($status == 'Pending'){
            $sortby = array('Send');
        }elseif($status == 'Accepted'){
            $sortby = array('Accepted');
        }elseif($status == 'Rejected'){
            $sortby = array('Rejected');
        }
        
        $applicationsData = array();
        $user_id = Auth::user()->id;
        if(Auth::user()->type == 'school'){
            $university = Universities::byUser($user_id)->first();
            $applications = $this->where('university_id', $university->id)->orderBy('id', 'desc')->get();
            foreach($applications as $key=>$val){
                $flagSortBy = false;
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id))
                        $application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                        if(in_array($application_statuses->name, $sortby)){
                            $applications[$key]->application_statuses = $application_statuses;
                            $flagSortBy = true;
                        }
                }
                // Get University array by university id
                $applications[$key]->university = $university;
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
                
                //Get Student array by student id ...
                $student = User::getUserAccountById($val->student_id);
                $applications[$key]->student = $student;
                if($flagSortBy)
                    $applicationsData[]= $applications[$key];
            }
        }else{
            $applications = $this->where('student_id', $user_id)->orderBy('id', 'desc')->get();
            
            foreach($applications as $key=>$val){
                $flagSortBy = false;
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id)){
                        $application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                        if(in_array($application_statuses->name, $sortby)){
                            $applications[$key]->application_statuses = $application_statuses;
                            $flagSortBy = true;
                        }
                    }    
                }
                // Get University array by university id
                $universities = new Universities();
                $applications[$key]->university = $universities->getUniversityByIdData($val->university_id);
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
                if($flagSortBy)
                    $applicationsData[]= $applications[$key];
            }
        }
        return $applicationsData;
    }   
    
    /**
     * Get Applications by Current user id With Sort By Update status
     * @param Current User id
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsFilterByStatusData($status, $isAllSelected){
    	
        $sortby = array();
        foreach($status as $val){
        	if($val['checked']){
			    if($val['value'] == 'Opened'){
		            array_push($sortby,"Review");
		        }elseif($val['value'] == 'Pending'){
		            array_push($sortby,"Send");
		        }elseif($val['value'] == 'Accepted'){
		            //array_push($sortby,"Accepted");
		            array_push($sortby,"Accepted", "Complete");
		        }elseif($val['value'] == 'Rejected'){
		            //array_push($sortby,"Rejected");
		            array_push($sortby,"Rejected", "Incomplete", "Documents");
		        }
		    }
        }
       
        $applicationsData = array();
        $user_id = Auth::user()->id;
        
        // Get application data By student id ...
        $applications = $this->where('student_id', $user_id)->orderBy('id', 'desc')->get();
        
        foreach($applications as $key=>$val){
            $flagSortBy = false;
            $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
            if(!empty($applications_application_statuses)){
                $applications[$key]->applications_application_statuses = $applications_application_statuses;
                if(!empty($applications_application_statuses->application_status_id)){
                    $application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                    // Check to all checkbox selected or not
                    if($isAllSelected){
						$applications[$key]->application_statuses = $application_statuses;
                        $flagSortBy = true;
					}elseif(in_array($application_statuses->name, $sortby)){
                        $applications[$key]->application_statuses = $application_statuses;
                        $flagSortBy = true;
                    }
                }    
            }
            // Get University array by university id
            $universities = new Universities();
            $applications[$key]->university = $universities->getUniversityByIdData($val->university_id);
            // Get Diploma array by diploma id
            $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
            if($flagSortBy)
                $applicationsData[]= $applications[$key];
        }
       
        return $applicationsData;
    }
    
    /**
     * Get Applications Search by Keyword ...
     * @param Current User id
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsSearchByKeywordData($keyword){
    	
        $user_id = Auth::user()->id;
        if(Auth::user()->type == 'school'){
            $university = Universities::byUser($user_id)->first();
            $applications = $this->select('applications.*')->where('applications.university_id', $university->id)
            					->leftJoin('users', 'users.id', '=', 'applications.student_id')
            					->where('users.name', 'like', '%' . $keyword . '%')
            					->orderBy('applications.id', 'desc')
            					->get();
            					
            foreach($applications as $key=>$val){
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id))
                        $applications[$key]->application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                }
                // Get University array by university id
                //$universities = new Universities();
                $applications[$key]->university = $university;
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
                
                //Get Student array by student id ...
                $student = User::getUserAccountById($val->student_id);
                $applications[$key]->student = $student;
            }
        }else{
            $applications = $this->select('applications.*')->where('applications.student_id', $user_id)
            					->leftJoin('universities', 'universities.id', '=', 'applications.university_id')
            					->where('universities.name', 'like', '%' . $keyword . '%')
            					->orderBy('applications.id', 'desc')
            					->get();
            
            foreach($applications as $key=>$val){
                $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $val->id)->orderBy('id', 'desc')->first();
                if(!empty($applications_application_statuses)){
                    $applications[$key]->applications_application_statuses = $applications_application_statuses;
                    if(!empty($applications_application_statuses->application_status_id))
                        $applications[$key]->application_statuses = ApplicationsStatuses::find($applications_application_statuses->application_status_id);
                }
                // Get University array by university id
                $universities = new Universities();
                $applications[$key]->university = $universities->getUniversityByIdData($val->university_id);
                // Get Diploma array by diploma id
                $applications[$key]->diplomas = Diplomas::find($val->diploma_id);
            }
        }
        return $applications;
    }
    
    /**
     * Get Application by application id ...
     * 
     * @return Mixed
     */
    public function getApplicationByIdData($application_id)
    {
        // Get application By application if using find method ...
        $applicationData = $this->find($application_id);
        
        // Unseralize remaining document records with id and status
        if(!empty($applicationData->required_document)){
            $applicationData->required_document = unserialize($applicationData->required_document);
        }
        
        $applications_application_statuses = ApplicationsApplicationStatuses::where('application_id', $applicationData->id)->orderBy('id', 'desc')->first();
        if(!empty($applications_application_statuses)){
            $applicationData->applications_application_statuses = $applications_application_statuses;
            if(!empty($applicationData->applications_application_statuses->application_status_id))
                $applicationData->application_statuses = ApplicationsStatuses::find($applicationData->applications_application_statuses->application_status_id);
        }
        
        $applications_application_statuses_All = ApplicationsApplicationStatuses::where('application_id', $applicationData->id)->orderBy('id', 'desc')->get();
        if(!empty($applications_application_statuses_All)){
            $application_statuses_name = array();
            foreach($applications_application_statuses_All as $key=>$val){
                if(!empty($val->application_status_id)){
                    $application_statuses = ApplicationsStatuses::find($val->application_status_id);
                    $application_statuses_name[] = $application_statuses->name;
                    $applications_application_statuses_All[$key]->application_statuses = $application_statuses;
                }
            }
            $applicationData->application_statuses_name = $application_statuses_name;
            $applicationData->applications_application_statuses_all = $applications_application_statuses_All;
        }
        
        // Get University array by university id
        $universities = new Universities();
        $applicationData->university = $universities->getUniversityByIdData($applicationData->university_id);
        
        // Get Diploma array by diploma id
        $diplomas = new Diplomas();
        $applicationData->diplomas = $diplomas->getProgramByIdData($applicationData->diploma_id);
                
        //Get Student array by student id ...
        if(Auth::user()->type == 'school'){
            $student = User::getUserAccountById($applicationData->student_id);
            $applicationData->student = $student;
        }
        
        //Get Application Document uploaded by student user...
        $applicationDocuments  = ApplicationDocuments::where(array('application_id' => $application_id))->get();
        if(!empty($applicationDocuments)){
            $document = array();
            foreach($applicationDocuments as $key=>$val){
                $applicationDocuments[$key]->studentDocuments = StudentDocuments::find($val->student_document_id);
                if(!empty($applicationDocuments[$key]->studentDocuments->document_type_id)){
                    $applicationDocuments[$key]->studentDocuments->DocumentTypes = DocumentTypes::find($applicationDocuments[$key]->studentDocuments->document_type_id);
                }
            }
            $applicationData->applicationDocuments = $applicationDocuments;
        }
        
        return $applicationData;
    }
    
    /**
     * Get TMAP Notification of update status by Current user id ...
     * 
     * @return Mixed
     */
    public function getUpdateStatusTrackNotificationData()
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->type == 'school'){
            $university = Universities::byUser($user_id)->first();
           
            $notification = DB::table('applications_application_statuses as aas')
            ->select('aas.*', 'a.*', 'as.*', 'a.message as a_message')
            ->leftJoin('applications as a', 'aas.application_id', '=', 'a.id')
            ->leftJoin('application_statuses as as', 'aas.application_status_id', '=', 'as.id')
            ->where('a.university_id', $university->id)
            //->where('as.name', 'Send')
            ->whereIn('as.name', array('Send','Documents'))
            ->orderBy('aas.id', 'desc')
            ->take(3)
            ->get();
            
            foreach($notification as $key=>$val){
                $notification[$key]->date_maj = date('d-m-y h:i A', strtotime($val->date_maj));
                // Get University array by university id
                $notification[$key]->university = $university;
                
                // Get Diploma array by diploma id
                $notification[$key]->diplomas = Diplomas::find($val->diploma_id);
                
                // Get student array by diploma id
                $student = User::getUserAccountById($val->student_id);
                $notification[$key]->student = $student;
            }
            //return $notification;
            
        }else{
            //ApplicationsApplicationStatuses::where('application_id', $applicationData->id)->orderBy('id', 'desc')->get();
            $notification = DB::table('applications_application_statuses as aas')
            ->select('aas.*', 'a.*', 'as.*', 'a.message as a_message')
            ->leftJoin('applications as a', 'aas.application_id', '=', 'a.id')
            ->leftJoin('application_statuses as as', 'aas.application_status_id', '=', 'as.id')
            ->where('a.student_id', $user_id)
            //->where('as.name','!=', 'Send')
            ->whereNotIn('as.name', array('Send','Documents'))
            ->orderBy('aas.id', 'desc')
            ->take(3)
            ->get();
           
            foreach($notification as $key=>$val){
                $notification[$key]->date_maj = date('d-m-y h:i A', strtotime($val->date_maj));
                // Get University array by university id
                $universities = new Universities();
                $notification[$key]->university = $universities->getUniversityByIdData($val->university_id);
                // Get Diploma array by diploma id
                $notification[$key]->diplomas = Diplomas::find($val->diploma_id);
            }
            //return $notification;
        }
        return $notification;
    }
    
    
    /**
     * Upload Document of application by student user...
     * @param $fileName, $application_id, $diploma_requirement_id
     * 
     * @return Mixed
     */
    public function uploadApplicationDocument($fileName, $application_id, $diploma_requirement_id, $document_type_id)
    {
        // Get application data By application id...
        $applicationData = $this->find($application_id);
        
        // Unseralize remaining document records with id and status
        if(!empty($applicationData->required_document)){
            $applicationData->required_document = unserialize($applicationData->required_document);
        }
        
        // Get Document type By string of "application" ...
        //$documentTypes = DocumentTypes::where('name', 'application')->first();
        //$documentTypes = DocumentTypes::find($document_type_id);
        
        // Get Diploma Requirements Document by diploma_requirement_id  ...
        $diplomaRequirements  = DiplomaRequirements::find($diploma_requirement_id);
        
        // Get Application Document by Application id and diploma_requirement_id ...
        $applicationDocuments  = ApplicationDocuments::where(array('application_id' => $application_id, 'diploma_requirement_id' => $diploma_requirement_id))->first();
        if(!empty($applicationDocuments)){
            // Update student application document by student document id ...
            $studentDocuments = StudentDocuments::find($applicationDocuments->student_document_id);
            $studentDocuments->document_type_id = $document_type_id;
            $studentDocuments->link = $fileName;
            $studentDocuments->save();
            
        }else{
            // Insert student application document
            $studentDocuments = new StudentDocuments();
            $studentDocuments->student_id = $applicationData->student_id;
            $studentDocuments->document_type_id = $document_type_id;
            $studentDocuments->name = $diplomaRequirements->name;
            $studentDocuments->link = $fileName;
            $studentDocuments->save();
            
            // Insert application document references 
            $applicationDocuments = new ApplicationDocuments();
            $applicationDocuments->application_id = $application_id;
            $applicationDocuments->student_document_id = $studentDocuments->id;
            $applicationDocuments->diploma_requirement_id = $diploma_requirement_id;
            $applicationDocuments->save();
        }
        // Update Application Status By application_id and student user when upload missing documents 
        if($this->updateApplicationStatus($application_id, 'Documents')){
            return true;    
        }
    }
    
    /**
     * Delete Applications using selected app by school user
     * @param $data
     * 
     * @return Mixed
     */
    public function deleteApplicationsData($data)
    {
    	$appIds = array();
    	if(!empty($data)){
			foreach($data as $id=>$val){
				if($val)
					$appIds[] = $id;	
			}	
		}
		//Delete multiple Application
		if(!empty($appIds)){
			$this->whereIn('id',$appIds)->delete();     
			return true;
		}else{
			return false;
		}	
    }
}
