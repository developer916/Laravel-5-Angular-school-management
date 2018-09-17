<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Universities;
use App\RefLanguage;
use App\Diplomas;
use App\DiplomaStudy;
use App\DiplomaLevelStudy;

class DiplomasController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Get all Diploma Study.
     *
     * @param  DiplomaStudy $diplomaStudy
     * @return \Illuminate\Http\Response
     */
    public function getDiplomaStudy(DiplomaStudy $diplomaStudy)
    {
        $diplomaStudy = $diplomaStudy->all();
        if(!empty($diplomaStudy)){
           return response()->json(['status' => true, 'diplomaStudy' => $diplomaStudy]);
        }else{
            return response()->json(['status' => false, 'error' => 'Diploma Study not found']);
        }
    }
    
    /**
     * Get all Reference Language.
     *
     * @param  RefLanguage $refLanguage
     * @return \Illuminate\Http\Response
     */
    public function getRefLanguage(RefLanguage $refLanguage)
    {
        $refLanguage = $refLanguage->get();
        if(!empty($refLanguage)){
           return response()->json(['status' => true, 'language' => $refLanguage]);
        }else{
            return response()->json(['status' => false, 'error' => 'Language not found']);
        }
    }
    
    /**
     * Get all diploma level of study.
     *
     * @param  DiplomaLevelStudy $diplomaLevelStudy
     * @return \Illuminate\Http\Response
     */
    public function getDiplomaLevelStudy(DiplomaLevelStudy $diplomaLevelStudy)
    {
        $diplomaLevelStudy = $diplomaLevelStudy->get();
        if(!empty($diplomaLevelStudy)){
           return response()->json(['status' => true, 'diplomaLevelStudy' => $diplomaLevelStudy]);
        }else{
            return response()->json(['status' => false, 'error' => 'Level of study not found']);
        }
    }
    
    /**
     * Get all Programs By University id ....
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    public function getPrograms(Diplomas $diplomas, Universities $universities)
    {
        if(Auth::check()){
            $universities = $universities->byUser(Auth::user()->id)->first();
            $programsData = $diplomas->getProgramData($universities->id);
            return response()->json(['status' => true, 'programs' => $programsData]);
        }
    }
    
    
    /**
     * Get Program by Program id ...
     * @param  $program_id, Diplomas $diplomas
     * 
     * @return Mixed
     */
    public function getProgramById($program_id, Diplomas $diplomas)
    {  
        if(Auth::check()){
            $diplomaData = $diplomas->getDiplomaByIdData($program_id);
            if(!empty($diplomaData)){
                return response()->json(['status' => true, 'program' => $diplomaData]);
            }else{
                return response()->json(['status' => false, 'error' => 'Campus not found']);
            }
        }
    }
    
    /**
     * Delete Program by Program id ...
     * @param  $program_id, Diplomas $diplomas
     * 
     * @return Mixed
     */
    public function deleteProgram($program_id, Diplomas $diplomas, Universities $universities)
    {  
        if(Auth::check()){
        	$diplomaDeleteData = $diplomas->deleteProgramData($program_id);
            //$diplomaDeleteData = $diplomas->destroy($program_id);
            //$universities = $universities->byUser(Auth::user()->id)->first();
            //$programsData = $diplomas->getProgramData($universities->id);
            //if(!empty($programsData) || $diplomaDeleteData){
                //return response()->json(['status' => true, 'programs' => $programsData, 'msg' => 'Program removed successfully.']);
            if($diplomaDeleteData){    
                return response()->json(['status' => true, 'msg' => 'Program removed successfully.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Program is not removed.']);
            }
        }
    }
    
    /**
     * Copy Program by Program id ...
     * @param $program_id, Diplomas $diplomas
     * 
     * @return Mixed
     */
    public function copyProgram($program_id, Diplomas $diplomas, Universities $universities)
    {  
        if(Auth::check()){
            $diplomaCopyId = $diplomas->copyProgramData($program_id);
            //$universities = $universities->byUser(Auth::user()->id)->first();
            //$programsData = $diplomas->getProgramData($universities->id);
            $programData = $diplomas->getDiplomaByIdData($diplomaCopyId);
            if(!empty($programData) && $diplomaCopyId){
                return response()->json(['status' => true, 'program' => $programData, 'msg' => 'Program copy successfully.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Program is not copy.']);
            }
        }
    }
    
    /*public function copyProgram($program_id, Diplomas $diplomas, Universities $universities)
    {  
        if(Auth::check()){
            $diplomaCopyData = $diplomas->copyProgramData($program_id);
            $universities = $universities->byUser(Auth::user()->id)->first();
            $programsData = $diplomas->getProgramData($universities->id);
            if(!empty($programsData) && $diplomaCopyData){
                return response()->json(['status' => true, 'programs' => $programsData, 'msg' => 'Program copy successfully.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Program is not copy.']);
            }
        }
    }*/
    
    /**
     * Get all Programs ...
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    public function getAllPrograms(Diplomas $diplomas)
    {
        //if(Auth::check()){
            $programData = $diplomas->getAllProgramData();
            return response()->json(['status' => true, 'programs' => $programData]);
        //}
    }
    
    /**
     * Get all Speciality of Programs(Diploma) ...
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    public function getAllSpeciality(Diplomas $diplomas)
    {
        //if(Auth::check()){
            $specialityData = $diplomas->getAllSpecialityData();
            return response()->json(['status' => true, 'speciality' => $specialityData]);
        //}
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveProgram(Request $request, Diplomas $diplomas, Universities $universities)
    {
        if(Auth::check()){
            $universities = $universities->byUser(Auth::user()->id)->first();
            if($universities->id){
                $data = $diplomas->saveProgramData($request->data, $universities->id);
                if (empty($data)) {
                    return response()->json(['status' => false, 'msg' => 'Program not saved.', ]);
                }
            }else{
                return response()->json(['status' => false, 'msg' => 'Program not saved.']);
            }
            $programData = $diplomas->getProgramData($universities->id);
            return response()->json(['status' => true, 'programs' => $programData, 'msg' => 'Program saved successfully.']);
        }
    }
    
    /**
     * upload Images.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function singleUploadImage(Request $request)
    {
        $file = $request->file('file');
        //$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
        $allowedMimes = array('application/pdf');
        if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
            $image_name = time()."-".$file->getClientOriginalName();
            $file->move('uploads/programs', $image_name);
            return response()->json(['msg' => true, 'data' => $image_name]);
        }else{
            return response()->json(['msg' => false, 'data' => 'File not found']); 
        }
    }
    
}
