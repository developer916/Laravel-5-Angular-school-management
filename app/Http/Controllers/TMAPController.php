<?php

namespace App\Http\Controllers;

use App\students;
use Illuminate\Http\Request;
use Auth;
use App\Applications;

class TMAPController extends Controller
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
     * Apply application by student of current user ...
     * 
     * @return check Messages
     */
    public function applyApplication(Request $request, Applications $applications)
    {
        if(Auth::check() && Auth::user()->type == 'student'){
            $data = $applications->SaveApplication($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Application not submitted.']);
            }
            return response()->json(['status' => true, 'msg' => 'Successfully Save Application Detail.']);
        }else{
            return response()->json(['status' => false, 'msg' => 'Only Student can submit application.']);
        }
    }
    
    /**
     * Get Applications by Current user id
     * 
     * @return Applications Array of mixed
     */
    public function getApplications(Applications $applications)
    {  
        if(Auth::check()){
            $applicationsData = $applications->getApplicationsData();
            if(!empty($applicationsData)){
                return response()->json(['status' => true, 'applications' => $applicationsData]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        }
    }
    
    /**
     * Get Applications by Current user id With sort by update status
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsSortByStatus($status, Applications $applications)
    {  
        if(Auth::check()){
            $applicationsData = $applications->getApplicationsSortByStatusData($status);
            if(!empty($applicationsData)){
                return response()->json(['status' => true, 'applications' => $applicationsData]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        }
    }
    
    /**
     * Get Applications by Current student user id With Filter by update status 
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsFilterByStatus(Request $request, Applications $applications)
    {  
        if(Auth::check()){
            $applicationsData = $applications->getApplicationsFilterByStatusData($request->status, $request->isAllSelected);
            if(!empty($applicationsData)){
                return response()->json(['status' => true, 'applications' => $applicationsData]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        }
    }
    
    /**
     * Get Applications Search By Keyword
     * 
     * @return Applications Array of mixed
     */
    public function getApplicationsSearchByKeyword(Request $request, Applications $applications)
    {  
        if(Auth::check()){
            $applicationsData = $applications->getApplicationsSearchByKeywordData($request->keyword);
            if(!empty($applicationsData)){
                return response()->json(['status' => true, 'applications' => $applicationsData]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        }
    }
    
    /**
     * Get Applications by Application id
     * 
     * @return Application Array of mixed
     */
    public function getApplicationById($application_id, Applications $applications)
    {  
        if(Auth::check()){
            $applicationData = $applications->getApplicationByIdData($application_id);
            if(!empty($applicationData)){
                return response()->json(['status' => true, 'application' => $applicationData]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        }
    }
    
    /**
     * Update application Status By current user login id
     * 
     * @return check Messages
     */
    public function updateApplication(Request $request, Applications $applications)
    {
        if(Auth::check()){
            $data = $applications->updateApplicationStatus($request->application_id, $request->status, $request->document);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Application status not updated.']);
            }
            if($request->page == 'checkProfile'){
                $applicationData = $applications->getApplicationByIdData($request->application_id);
                return response()->json(['status' => true, 'msg' => 'Successfully update application status.', 'application' => $applicationData ]);
            }else{
                $applicationsData = $applications->getApplicationsData();
                return response()->json(['status' => true, 'msg' => 'Successfully update application status.', 'applications' => $applicationsData ]);
            }
            
        }else{
            return response()->json(['status' => false, 'msg' => 'Application status not updated.']);
        }
    }
    
    /**
     * Get TMAP Notification of update status by Current user id
     * 
     * @return TMAP Notification Array of mixed
     */
    public function getUpdateStatusTrackNotification(Applications $applications)
    {  
        if(Auth::check()){
            $notification = $applications->getUpdateStatusTrackNotificationData();
            if(!empty($notification)){
                return response()->json(['status' => true, 'notification' => $notification]);
            }else{
                return response()->json(['status' => false, 'msg' => 'Notification not found.']);
            }
        }
    }
    
    
    /**
     * Upload Application Document by student user with store database..
     * @param Request $request
     * @return Document name
     */ 
    public function uploadDocument(Request $request, Applications $applications)
    {
        //echo $request->application_id.'---'.$request->diploma_requirement_id;
        $file = $request->file('file');
        //$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
        //if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
        if ( $file->isValid() ) {
            $fileName = time()."-".$file->getClientOriginalName();
            $file->move('uploads/documents', $fileName);
            $data = $applications->uploadApplicationDocument($fileName, $request->application_id, $request->diploma_requirement_id, $request->document_type_id);
            if($data){
                //$userData = User::getUserAccountById(Auth::user()->id);
                return response()->json(['msg' => true, 'data' => $fileName]);
            }
    	}else{
			return response()->json(['msg' => false, 'data' => 'Data not found']); 
		}  	
    }
    
    /**
     * Upload Application Document by student user when send application..
     * @param Request $request
     * @return Document name
     */ 
    public function uploadDocumentSend(Request $request, Applications $applications)
    {
        $file = $request->file('file');
        //$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
        //if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
        if ( $file->isValid() ) {
            $fileName = time()."-".$file->getClientOriginalName();
            $file->move('uploads/documents', $fileName);
            return response()->json(['msg' => true, 'data' => $fileName]);
        }else{
	    	return response()->json(['msg' => false, 'data' => 'Data not found']); 
		}  	
    }
    
    /**
     * Delete Application using selected app by school user..
     * @param Request $request
     * @return mixed Application list...
     */ 
    public function deleteApplications(Request $request, Applications $applications)
    {
        if(Auth::check()){
        	
        	$data = $applications->deleteApplicationsData($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Application is not remove.']);
            }
            	
            $applicationsData = $applications->getApplicationsData();
            if(!empty($applicationsData)){
                return response()->json(['status' => true, 'applications' => $applicationsData, 'msg' => 'Application removed successfully.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Application data not found.']);
            }
        } 	
    }
}
