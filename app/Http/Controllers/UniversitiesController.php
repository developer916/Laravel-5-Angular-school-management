<?php

namespace App\Http\Controllers;

use App\Universities;
use Illuminate\Http\Request;
use Auth;
use App\addresses;
use App\contacts;
use App\UniversitiesPictures;

class UniversitiesController extends Controller
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
    public function show(Universities $universities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function edit(Universities $universities)
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
    public function update(Request $request, Universities $universities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Universities $universities)
    {
        //
    }
    
    /**
     * Get All Universities ...
     * 
     * @return Mixed
     */
    public function getUniversities(Universities $universities)
    {  
        $universitiesData = $universities->getUniversitiesData();
        if(!empty($universitiesData)){
            return response()->json(['status' => true, 'universities' => $universitiesData]);
        }else{
            return response()->json(['status' => false, 'msg' => 'Data not found']);
        }      
    }
    
    /**
     * Get University by current user id ...
     * 
     * @return Mixed
     */
    public function getUniversity(Universities $universities)
    {  
        if(Auth::check()){
            $universityData = $universities->getUniversityData();
            if(!empty($universityData)){
                return response()->json(['auth' => $universityData]);
            }else{
                return response()->json(['error' => 'User not found']);
            }
        }
    }
    
    
    /**
     * Get University by University id ...
     * 
     * @return Mixed
     */
    public function getUniversityById($university_id, Universities $universities)
    {  
        if(Auth::check()){
            $universityData = $universities->getUniversityByIdData($university_id);
            if(!empty($universityData)){
                return response()->json(['status' => true, 'university' => $universityData]);
            }else{
                return response()->json(['status' => false, 'error' => 'User not found']);
            }
        }
    }
    
    /**
     * Save University profile detail by current user id ...
     * 
     * @return Mixed
     */
    public function saveProfile(Request $request, Universities $universities)
    {
        if(Auth::check()){
            $data = $universities->saveProfileData($request->data,$request->step);
            if (empty($data)) {
                return response()->json(['error' => 'user not found']);
            }
            $universityData = $universities->getUniversityData();
            return response()->json(['auth' => $universityData]);
        }
    }
    
    /**
     * Universities upload Images.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function universityUploadImages(Request $request)
    {
        $files = $request->file('file');
        $imagesName = array();
        if(!empty($files)){
            foreach($files as $file){
                $allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
                if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
                    $image_name = time()."-".$file->getClientOriginalName();
                    $file->move('uploads/universities', $image_name);
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
     * Save University Front page detail by current user id ...
     * 
     * @return Mixed
     */
    public function saveFrontpage(Request $request, Universities $universities)
    {
        if(Auth::check()){
            $data = $universities->saveFrontpageData($request->data);
            if (empty($data)) {
                return response()->json(['status' => false, 'msg' => 'Universities Front page not saved.', ]);
            }
            $universityData = $universities->getUniversityData();
            return response()->json(['status' => true, 'university' => $universityData, 'msg' => 'Universities Front page saved successfully.']);
        }
        
            
    }
    
    
    /**
     * Delete UniversityImages From folder and Database ....
     * 
     * @return Mixed
     */
    public function deleteUniversityImages(Request $request, Universities $universities, UniversitiesPictures $universitiesPictures)
    {
        if(Auth::check()){
            $fileName = 'uploads/universities/'.$request->fileName;
            if($request->type == 'ufile' && file_exists($fileName)){
                unlink($fileName);
                return response()->json(['status' => true, 'type' => $request->type, 'msg' => 'Remove Images Successfully.']);
            }else{
                if(file_exists($fileName)){
                    unlink($fileName);
                }
                $universitiesPictures->destroy($request->imageId);
                $universityData = $universities->getUniversityData();
                return response()->json(['status' => true, 'type' => $request->type, 'university' => $universityData, 'msg' => 'Remove Images Successfully.']);
            }
        }      
    }
    
    
    /**
     * Search Universtiy by filter on map ....
     * 
     * @return Mixed
     */
    public function searchUniversities(Request $request, Universities $universities)
    {
        //if(Auth::check()){
            $universitiesData  = $universities->searchUniversitiesData($request->data);
            if (!empty($universitiesData) && count($universitiesData) > 0) {
                return response()->json(['status' => true, 'universities' => $universitiesData , 'msg' => 'Universities found.']);
            }else{
		    	return response()->json(['status' => false, 'msg' => 'Universities not found.', ]);
			}
            
        //}
    }
    
    /**
     * Filter Universtiy price by semester on map ....
     * 
     * @return Mixed
     */
    public function filterUniversitiesByPrice(Request $request, Universities $universities)
    {
        //if(Auth::check()){
            $universitiesData  = $universities->filterUniversitiesByPriceData($request->data);
            if (!empty($universitiesData) && count($universitiesData) > 0) {
                return response()->json(['status' => true, 'universities' => $universitiesData , 'msg' => 'Universities found.']);
            }else{
		    	return response()->json(['status' => false, 'msg' => 'Universities not found.', ]);
			}
            
        //}
    }
    
    /**
     * Filter Universtiy By Icons on map ....
     * 
     * @return Mixed
     */
    public function filterUniversitiesByIcons(Request $request, Universities $universities)
    {
        //if(Auth::check()){
            $universitiesData  = $universities->filterUniversitiesByIconsData($request->data);
            if (!empty($universitiesData) && count($universitiesData) > 0) {
                return response()->json(['status' => true, 'universities' => $universitiesData , 'msg' => 'Universities found.']);
            }else{
		    	return response()->json(['status' => false, 'msg' => 'Universities not found.', ]);
			}
            
        //}
    }
    
    
}
