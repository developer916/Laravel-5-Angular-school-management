<?php

namespace App\Http\Controllers;

use App\students;
use Illuminate\Http\Request;
use Auth;
use App\addresses;
use App\contacts;
use App\refCountry;
use App\StudentEducations;
use App\StudentExperiences;
use App\StudentLanguages;
use App\DocumentTypes;
use App\StudentDocuments;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function show(students $students)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function edit(students $students)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, students $students)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function destroy(students $students)
    {
        //
    }
    
    /**
     * Get All Reference Country ...
     * 
     * @return Country
     */
    public function getRefCountry(refCountry $refCountry)
    {  
        $refCountryData = $refCountry->all();
        if(!empty($refCountryData)){
            return response()->json(['status' => true, 'refCountry' => $refCountryData]);
        }else{
            return response()->json(['status' => false, 'msg' => 'Data not found']);
        }      
    }
    
    /**
     * Get Educations Detail by current user id ...
     * 
     * @return Educations Array
     */
    public function getEducations(StudentEducations $studentEducations)
    {  
        if(Auth::check()){
            $educations = $studentEducations->getEducationsData();
            if(!empty($educations)){
                return response()->json(['status' => true, 'educations' => $educations]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Get Educations Detail by Student user id ...
     * 
     * @return Educations Array
     */
    public function getEducationsByStudentId($student_id, StudentEducations $studentEducations)
    {  
        if(Auth::check()){
            $educations = $studentEducations->getEducationsData($student_id);
            if(!empty($educations)){
                return response()->json(['status' => true, 'educations' => $educations]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    
    /**
     * Get Education Detail by Education id ...
     * 
     * @return Education Array
     */
    public function getEducation($education_id, StudentEducations $studentEducations)
    {  
        if(Auth::check()){
            $education = $studentEducations->getEducationById($education_id);
            if(!empty($education)){
                return response()->json(['status' => true, 'education' => $education]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Save Education Detail for current user ...
     * 
     * @return Education Array
     */
    public function saveEducation(Request $request, StudentEducations $studentEducations)
    {
        if(Auth::check()){
            $data = $studentEducations->saveEducationData($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Education detail is wrong.']);
            }
            $educations = $studentEducations->getEducationsData();
            return response()->json(['status' => true, 'msg' => 'Successfully Save Education Detail.', 'educations' => $educations]);
        }
    }
    
    /**
     * Delete Education by Education id ...
     * 
     * @return Educations Array
     */
    public function deleteEducation($education_id, StudentEducations $studentEducations)
    {  
        if(Auth::check()){
        	$deleteEducation = $studentEducations->destroy($education_id);
            $educations = $studentEducations->getEducationsData();
            if(!empty($educations) || $deleteEducation ){
                return response()->json(['status' => true, 'educations' => $educations, 'msg' => 'Education successfully removed.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Education is not removed.']);
            }
        }
    }
    
    /**
     * Get Experiences Detail by current user id ...
     * 
     * @return Experiences Array
     */
    public function getExperiences(StudentExperiences $studentExperiences)
    {  
        if(Auth::check()){
            $experiences = $studentExperiences->getExperiencesData();
            if(!empty($experiences)){
                return response()->json(['status' => true, 'experiences' => $experiences]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Get Experiences Detail by student id...
     * 
     * @return Experiences Array
     */
    public function getExperiencesByStudentId($student_id, StudentExperiences $studentExperiences)
    {  
        if(Auth::check()){
            $experiences = $studentExperiences->getExperiencesData($student_id);
            if(!empty($experiences)){
                return response()->json(['status' => true, 'experiences' => $experiences]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Get Experience Detail by Experience id ...
     * 
     * @return Experience Array
     */
    public function getExperience($experience_id, StudentExperiences $studentExperiences)
    {  
        if(Auth::check()){
            $experience = $studentExperiences->getExperienceById($experience_id);
            if(!empty($experience)){
                return response()->json(['status' => true, 'experience' => $experience]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    
    /**
     * Save Experience Detail for current user ...
     * 
     * @return Experience Array
     */
    public function saveExperience(Request $request, StudentExperiences $studentExperiences)
    {
        if(Auth::check()){
            $data = $studentExperiences->saveExperienceData($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Experiences detail is wrong.']);
            }
            $experiences = $studentExperiences->getExperiencesData();
            return response()->json(['status' => true, 'msg' => 'Successfully Save Experiences Detail.', 'experiences' => $experiences]);
        }
    }
    
    /**
     * Delete Experience by Experience id ...
     * 
     * @return Experiences Array
     */
    public function deleteExperience($experience_id, StudentExperiences $studentExperiences)
    {  
        if(Auth::check()){
        	$deleteExperience = $studentExperiences->destroy($experience_id);
            $experiences = $studentExperiences->getExperiencesData();
            if(!empty($experiences) || $deleteExperience ){
                return response()->json(['status' => true, 'experiences' => $experiences, 'msg' => 'Experience successfully removed.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Experience is not removed.']);
            }
        }
    }
    
    /**
     * Get Languages Detail by current user id ...
     * 
     * @return Languages Array
     */
    public function getLanguages(StudentLanguages $studentLanguages)
    {  
        if(Auth::check()){
            $languages = $studentLanguages->getLanguagesData();
            if(!empty($languages)){
                return response()->json(['status' => true, 'languages' => $languages]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Get Languages Detail by Student Id ...
     * 
     * @return Languages Array
     */
    public function getLanguagesByStudentId($student_id, StudentLanguages $studentLanguages)
    {  
        if(Auth::check()){
            $languages = $studentLanguages->getLanguagesData($student_id);
            if(!empty($languages)){
                return response()->json(['status' => true, 'languages' => $languages]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Get Language Detail by Language id ...
     * 
     * @return Language Array
     */
    public function getLanguage($language_id, StudentLanguages $studentLanguages)
    {  
        if(Auth::check()){
            $language = $studentLanguages->getLanguageById($language_id);
            if(!empty($language)){
                return response()->json(['status' => true, 'language' => $language]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Data not found.']);
            }
        }
    }
    
    /**
     * Save Language Detail for current user ...
     * 
     * @return Language Array
     */
    public function saveLanguage(Request $request, StudentLanguages $studentLanguages)
    {
        if(Auth::check()){
            $data = $studentLanguages->saveLanguageData($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Languages detail is wrong.']);
            }
            $languages = $studentLanguages->getLanguagesData();
            return response()->json(['status' => true, 'msg' => 'Successfully Save Languages Detail.', 'languages' => $languages]);
        }
    }
    
    /**
     * Delete Language by Language id ...
     * 
     * @return Languages Array
     */
    public function deleteLanguage($language_id, StudentLanguages $studentLanguages)
    {  
        if(Auth::check()){
        	$deleteLanguage = $studentLanguages->destroy($language_id);
            $languages = $studentLanguages->getLanguagesData();
            if(!empty($languages) || $deleteLanguage ){
                return response()->json(['status' => true, 'languages' => $languages, 'msg' => 'Language successfully removed.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Language is not removed.']);
            }
        }
    }
    
    /**
     * Get Document Type list ...
     * 
     * @return Document Type Array
     */
    public function getDocumentTypes(DocumentTypes $documentTypes)
    {  
        $data = $documentTypes->all();
        if(!empty($data)){
            return response()->json(['status' => true, 'documentTypes' => $data]);
        }else{
            return response()->json(['status' => false, 'msg' => 'Data not found.']);
        }    
    }
    
    
    /**
     * Get uploaded Basic Documents with document Type ...
     * 
     * @return Documents with Type Array
     */
    public function getBasicDocuments(StudentDocuments $studentDocuments){
		$documents = $studentDocuments->getBasicDocuments();
		if(!empty($documents)){
            return response()->json(['status' => true, 'documents' => $documents, 'msg' => 'Document is found.']);
    	}else{
			return response()->json(['status' => false, 'msg' => 'Document is not found.']); 
		}  
	}
    
    /**
     * Upload Basic Documents of student user with store database..
     * @param Request $request
     * @return profile image
     */ 
    public function uploadBasicDocument(Request $request, StudentDocuments $studentDocuments)
    {
    	if(Auth::check()){
	    	$file = $request->file('file');
	    	$fileType = $request->input('file-type');
	    	
	    	//if($fileType == 'Picture'){
				//$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
				//if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
				//}	
			//}
			
			if ( $file->isValid() ) {
	            $fileName = time()."-".$file->getClientOriginalName();
	            $file->move('uploads/documents', $fileName);
	            $uploadDocumentId = $studentDocuments->uploadBasicDocument($fileName, $request->fileType);
	            $documents = $studentDocuments->getBasicDocuments();
	            if(!empty($uploadDocumentId) && !empty($documents)){
	                return response()->json(['status' => true, 'documents' => $documents, 'msg' => 'Document successfully uploaded.']);
	            }
	    	}else{
				return response()->json(['status' => false, 'msg' => 'Document is not uploaded.']); 
			}  	
	   	}
    }
    
    /**
     * Delete Basic Document by Document id ...
     * 
     * @return Documents Array
     */
    public function deleteBasicDocument($document_id, StudentDocuments $studentDocuments)
    {  
        if(Auth::check()){
        	$documentData = $studentDocuments->find($document_id);
        	$deleteDocument = $studentDocuments->destroy($document_id);        	
            $documents = $studentDocuments->getBasicDocuments();
            if(!empty($documents) || $deleteDocument ){
            	$fileName = 'uploads/documents/'.$documentData->link;
	        	if(!empty($documentData->link) && file_exists($fileName)){
					unlink($fileName);
				}
                return response()->json(['status' => true, 'documents' => $documents, 'msg' => 'Document successfully removed.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Document is not removed.']);
            }
        }
    }
}
