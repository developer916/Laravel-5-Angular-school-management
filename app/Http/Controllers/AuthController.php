<?php
/**
 * Created by PhpStorm.
 * User: flore
 * Date: 30/10/2016
 * Time: 19:21
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use Hash;
use App\Models\User;
use App\Universities;
//use App\addresses;

class AuthController extends Controller
{

    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
        //$this->middleware('auth:api');
        //if(Auth::check()){}
        //$this->middleware('guest');
    }

    public function index()
    {
        //TODO
    }

    public function authenticate(Request $request)
    {
    	//echo $password = Hash::make('archirayan57'); exit;
        $credentials = $request->only('email', 'password');
		
        try {
            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	//return response()->json(compact('token'));
	$password = Hash::make(Auth::user()->email);
        return response()->json(['token'=>$password,'auth' => Auth::user()]);
    }
    
    public function authenticateLogin(Request $request, Universities $universities)
    {
    	//echo $password = Hash::make('archirayan40'); exit;
        $credentials = $request->only('email', 'password');
		
        try {
            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
		//return response()->json(compact('token'));
		$password = Hash::make(Auth::user()->email);
		//return response()->json(['token'=>$password,'auth' => Auth::user()]);
		$data = User::getUserAccountById(Auth::user()->id);
		//return response()->json(['token'=>$password,'auth' => $data]);
	        $response = array('token'=>$password,'auth' => $data);
		if(Auth::user()->type == 'school'){
		    $response['university'] = $universities->getUniversityData();
		}
		return response()->json($response);
    }
    
    public function register(Request $request)
    {
    	$credentials = $request->only('email', 'password', 'confirmed', 'name', 'type');
    	
    	User::register($credentials);
        
        $credentials1 = $request->only('email', 'password');
	try {
            if (!Auth::attempt($credentials1)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
	$password = Hash::make(Auth::user()->email);
        //return response()->json(['token'=>$password,'auth' => Auth::user()]);
	$data = User::getUserAccountById(Auth::user()->id);
        return response()->json(['token'=>$password,'auth' => $data]);    
    }

    public function getAuthenticatedUser()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['error' => 'user_not_found'], 500);
        }

        return response()->json(compact('user'));
    }
    
    public function getAuthenticatedUserData(Request $request)
    {
	$user = $request->only('email');
	$data = User::getUser($user['email']);
	//$data = User::getUserById(Auth::user()->id);
	if (empty($data)) {
            return response()->json(['error' => 'user_not_found'], 500);
        }
        unset($data->password);
        return response()->json(['auth' => $data]); 	 
    }
    
    public function getAuthenticatedUserAccountData()
    {
	if(Auth::user()->id){
	    $data = User::getUserAccountById(Auth::user()->id);
	}
	if (empty($data)) {
            return response()->json(['error' => 'user_not_found'], 500);
        }
        unset($data->password);
        return response()->json(['auth' => $data]); 	 
    }
    
    /**
     * Upload Profile picture of school user..
     * @param Request $request
     * @return  profile image
     */ 
    public function uploadImage(Request $request)
    {
    	$file = $request->file('file');
    	$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
        if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
	        $image_name = time()."-".$file->getClientOriginalName();
	        $file->move('uploads', $image_name);
	        return response()->json(['msg' => true, 'data' => $image_name]); 
    	}else{
		return response()->json(['msg' => false, 'data' => 'Data not found']); 
	}  	
    }
    
    /**
     * Upload Profile picture of student user with store database..
     * @param Request $request
     * @return profile image
     */ 
    public function uploadPicture(Request $request, User $user)
    {
    	$file = $request->file('file');
    	$allowedMimes = array('image/gif','image/png','image/jpg','image/jpeg');
        if ( $file->isValid() && in_array($file->getMimeType(), $allowedMimes) ) {
	        $image_name = time()."-".$file->getClientOriginalName();
	        $file->move('uploads', $image_name);
		$data = $user->updateProfilePicture($image_name);
		if($data){
		    //$userData = User::getUserAccountById(Auth::user()->id);
		    return response()->json(['msg' => true, 'data' => $image_name]);
		}
    	}else{
		return response()->json(['msg' => false, 'data' => 'Data not found']); 
	}  	
    }
    
    public function saveProfile(Request $request)
    {
    	$profile = $request->only('email','data');
    	$data = User::saveProfileData($profile);
    	if (empty($data)) {
            return response()->json(['error' => 'user_not_found'], 500);
        }
        return response()->json(['auth' => $data]); 	 
    }
    
    public function updatePassword(Request $request)
    {	
    	$requestData = $request->only('user_id','password','new_password','confirmed');
	if(Auth::check()){
	    $user = User::getUserById(Auth::user()->id);	   
	    /*
	    * Validate all input fields
            */
            //$this->validate($request, [
            //    'password' => 'required_with:new_password|password|max:8',
            //    'new_password' => 'confirmed|max:8',
            //]);
	    
	    if($request->new_password == $request->confirmed){
	   	if (Hash::check($request->password, $user->password)) {
		    $newPassword = Hash::make($request->new_password);
		    if(User::updatePasswordData(Auth::user()->id,$newPassword)){
			//$user->fill(['password' => Hash::make($request->new_password)])->save();
			return response()->json(['status' => true, 'msg' => 'Password successfuly changed.', 'token'=>$newPassword, 'auth' => Auth::user()]);
		    } else {
			return response()->json(['status' => false, 'msg' => 'Password is wrong.']);
		    }
		} else {
		   return response()->json(['status' => false, 'msg' => 'Old Password do not match.']);
		}
	    }else{
		return response()->json(['status' => false, 'msg' => 'Confirm Password do not match.']);
	    }
	}		 
    }
    
    public function updateAccount(Request $request)
    { 
   		//$requestData = $request->only('firstname','lastname','email','phone','Bdate','address');
		$requestData = $request->only('firstname','lastname','email','phone','Bdate','address', 'ref_country_id');
		if(Auth::check()){    
		    
		    $userData = User::getUserById(Auth::user()->id);	   
		    $user = User::where('email', '=', $request->email)->get();
		  
		    if(count($user)> 0 && $request->email != $userData->email){
			return response()->json(['status' => false, 'msg' => 'Email is already exist.']); 
		    }
		    
		    $data = User::updateAccountData(Auth::user()->id, $requestData);
		    if (empty($data)) {
				return response()->json(['status' => false, 'msg' => 'Account detail is wrong.']);
		    }
		    //$userData = User::getUserById(Auth::user()->id);
		    return response()->json(['status' => true, 'msg' => 'Successfuly save account detail.', 'auth' =>$data]);	    
		}		 
    }
}