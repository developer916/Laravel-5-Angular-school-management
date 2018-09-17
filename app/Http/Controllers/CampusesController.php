<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Universities;
use App\addresses;
use App\Campuses;
use App\Amenities;
use App\CampusRoomTypePictures;
use App\CampusDormPictures;
use App\CampusPictures;

class CampusesController extends Controller
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
     * Get all Campuses By University id.
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    public function getCampuses(Campuses $campuses, Universities $universities)
    {
        if(Auth::check()){
            $universities = $universities->byUser(Auth::user()->id)->first();
            $campusesData = $campuses->getCampusesData($universities->id);
            return response()->json(['status' => true, 'campuses' => $campusesData]);
        }
    }
    
    /**
     * Get all Campuses By Campus id.
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    /*public function getCampus($campus_id, Campuses $campuses)
    {
        if(Auth::check()){
            $universities = $universities->byUser(Auth::user()->id)->first();
            $campusesData = $campuses->getCampusesData($campus_id, $universities->id);
            return response()->json(['status' => true, 'campuses' => $campusesData]);
        }
    }*/
    
    /**
     * Get Campus by Campus id ...
     * 
     * @return Mixed
     */
    public function getCampusById($campus_id, Campuses $campuses)
    {  
        if(Auth::check()){
            $campusData = $campuses->getCampusByIdData($campus_id);
            if(!empty($campusData)){
                return response()->json(['status' => true, 'campus' => $campusData]);
            }else{
                return response()->json(['status' => false, 'error' => 'Campus not found']);
            }
        }
    }
    
    /**
     * Delete Campus by Campus id ...
     * 
     * @return Mixed
     */
    public function deleteHousing($campus_id, Campuses $campuses, Universities $universities)
    {  
        if(Auth::check()){
            $campusDeleteData = $campuses->deleteCampuseData($campus_id);
            //$campusDeleteData = $campuses->destroy($campus_id);
            //$universities = $universities->byUser(Auth::user()->id)->first();
            //$campusesData = $campuses->getCampusesData($universities->id);
            //if(!empty($campusesData) || $campusDeleteData){
            //    return response()->json(['status' => true, 'campuses' => $campusesData, 'msg' => 'Housing removed successfully.']);
            if($campusDeleteData){
                return response()->json(['status' => true, 'msg' => 'Housing removed successfully.']);
            }else{
                return response()->json(['status' => false, 'msg' => 'Housing is not removed.']);
            }
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveHousing(Request $request, Campuses $campuses, Universities $universities)
    {
        if(Auth::check()){
            $universities = $universities->byUser(Auth::user()->id)->first();
            if($universities->id){
                $data = $campuses->saveCampusesData($request->data, $universities->id);
                if (empty($data)) {
                    return response()->json(['status' => false, 'msg' => 'Campus and rooms not saved.', ]);
                }
            }else{
                return response()->json(['status' => false, 'msg' => 'Campus and rooms not saved.']);
            }
            $campusesData = $campuses->getCampusesData($universities->id);
            return response()->json(['status' => true, 'campuses' => $campusesData, 'msg' => 'Campus and rooms saved successfully.']);
        }
    }
    
    /**
     * Get all Amenities.
     *
     * @param  Amenities $amenities
     * @return \Illuminate\Http\Response
     */
    public function getAmenities(Amenities $amenities)
    {
        $amenities = $amenities->get();
        if(!empty($amenities)){
           return response()->json(['status' => true, 'amenities' => $amenities]);
        }else{
            return response()->json(['status' => false, 'error' => 'Amenities not found']);
        }
    }
    
    /**
     * upload Images.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImages(Request $request)
    {
        $files = $request->file('file');
        $imagesName = array();
        if(!empty($files)){
            foreach($files as $file){
                $allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
                if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
                    $image_name = time()."-".$file->getClientOriginalName();
                    $file->move('uploads/campuses', $image_name);
                    $imagesName[] = $image_name;
                    //return response()->json(['msg' => true, 'data' => $image_name]); 
                }else{
                    //return response()->json(['msg' => false, 'data' => 'Data not found']); 
                }
            }
            return response()->json(['msg' => true, 'data' => $imagesName]); 
        }
    }
    
    /**
     * Delete Campus Room Images From folder and Database ....
     * 
     * @return Mixed
     */
    public function deleteCampusRoomImages(Request $request, CampusRoomTypePictures $campusRoomTypePictures)
    {
        if(Auth::check()){
            $fileName = 'uploads/campuses/'.$request->fileName;
            if($request->type == 'rfile' && file_exists($fileName)){
                unlink($fileName);
                return response()->json(['status' => true, 'type' => $request->type, 'msg' => 'Remove Images Successfully.']);
            }else{
                if(file_exists($fileName)){
                    unlink($fileName);
                }
                $campusRoomTypePictures->destroy($request->imageId);
                $campusRoomTypePicturesData = $campusRoomTypePictures->where('campus_room_type_id',$request->campusRoomTypeId)->get();
                return response()->json(['status' => true, 'type' => $request->type, 'campusRoomTypePictures' => $campusRoomTypePicturesData, 'msg' => 'Remove Images Successfully.']);
            }
        }      
    }
    
    /**
     * Delete Campus Dorm Images From folder and Database ....
     * 
     * @return Mixed
     */
    public function deleteCampusDormImages(Request $request, CampusDormPictures $campusDormPictures)
    {
        if(Auth::check()){
            $fileName = 'uploads/campuses/'.$request->fileName;
            if($request->type == 'file' && file_exists($fileName)){
                unlink($fileName);
                return response()->json(['status' => true, 'type' => $request->type, 'msg' => 'Remove Images Successfully.']);
            }else{
                if(file_exists($fileName)){
                    unlink($fileName);
                }
                $campusDormPictures->destroy($request->imageId);
                $campusDormPicturesData = $campusDormPictures->where('campus_id',$request->campusId)->get();
                return response()->json(['status' => true, 'type' => $request->type, 'campusDormPictures' => $campusDormPicturesData, 'msg' => 'Remove Images Successfully.']);
            }
        }      
    }
    
    /**
     * Delete Campus Images From folder and Database ....
     * 
     * @return Mixed
     */
    public function deleteCampusImages(Request $request, CampusPictures $campusPictures)
    {
        if(Auth::check()){
            $fileName = 'uploads/campuses/'.$request->fileName;
            if($request->type == 'file' && file_exists($fileName)){
                unlink($fileName);
                return response()->json(['status' => true, 'type' => $request->type, 'msg' => 'Remove Images Successfully.']);
            }else{
                if(file_exists($fileName)){
                    unlink($fileName);
                }
                $campusPictures->destroy($request->imageId);
                $campusPicturesData = $campusPictures->where('campus_id',$request->campusId)->get();
                return response()->json(['status' => true, 'type' => $request->type, 'campusPictures' => $campusPicturesData, 'msg' => 'Remove Images Successfully.']);
            }
        }      
    }
}
