<?php
namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\addresses;
use App\refCountry;
use App\students;
use App\contacts;
use DateTime;
use Auth;
use App\Universities;
use Carbon\Carbon;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;
    //use SoftDeletes;
    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'recommandation_id',
        'firstname',
        'lastname',
        'contact_date',
        'comment'
    ];

    protected $guarded = [];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function drivingStudent() {
        return $this->hasOne('App\Models\DrivingStudent', 'id_driving_student', 'id');
    }

    public function drivingInstructor() {
        return $this->hasOne('App\Models\DrivingInstructor', 'id_driving_instructor', 'id');
    }

    public function addresses() {
        return $this->hasMany('App\Models\Address', 'user_id', 'id');
    }

    public function availabilities() {
        return $this->hasMany('App\Models\Availability', 'user_id', 'id');
    }

    public function contacts() {
        return $this->hasMany('App\Models\Contact', 'user_id', 'id');
    }

    public function refRecommandation() {
        return $this->hasOne('App\Models\RefRecommandation', 'recommandation_id', 'id_recommandation');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($user) {
            $user->contacts()->delete();
            $user->addresses()->delete();
            $user->availabilities()->delete();
        });
    }
    public static function register($data) {
	$name = !empty($data['name']) ? explode(' ', $data['name']) : '';
    	$result = DB::table('users')->insert( [
		    	'admin_role_id' => 0,
			'img_profile_link' => '',
			'name' => $data['name'],
			'firstname' => !empty($name[0])? $name[0] : '',
			'middlename' => !empty($name[1])? $name[1] : '',
			'lastname' => !empty($name[2])? $name[2] : '',
		    	'email' => $data['email'], 
		    	'gender' => !empty($data['gender'])? $data['gender'] : 0,
			'password' => Hash::make($data['password']), 
		    	'type' => $data['type'],
			'api_token' => '',
		    	'updated_at' => date('Y-m-d h:i:s'), 
		    	'created_at' => date('Y-m-d h:i:s')
		    	]
		);
	if($result && $data['type'] == 'student'){
	    $user = self::getUser($data['email']);
	    DB::table('students')->insert([
		'user_id'			=> $user->id, 
		'address_first_id' 		=> 0,
		'address_second_id' 		=> 0,
		'diploma_grade_id' 		=> 0,
		'ref_country_id' 		=> '',
		'birth_date' 			=> date('Y-m-d'),
		'profile_percent_complete' 	=> 0,
		'student_passport_id' 		=> 0,
	    ]);
	}
	return $result;
    	
    }
    public static function getUser($email) {
    	if($email){
	    $data = DB::table('users')->where('email', $email)->first();
	    $student = self::getStudent($data->id);
	    if($student){
		$data->Bdate = date('d-m-Y',strtotime($student->birth_date));
	    }
	    return $data;
	}
    }
    
    public static function calculateAge($birthdate){
		/*$birth_date     = new DateTime($birthdate);
		$current_date   = new DateTime();
		$diff = $birth_date->diff($current_date);
		$calculateAge = array(
				    'years'	=> $diff->y,
				    'months'	=> $diff->m,
				    'days'	=> $diff->d,
				);*/
		$birth_date = new Carbon($birthdate);
		$current_date = new Carbon();
		$diff['d'] =!empty($birth_date->diffInHours($current_date)) ? $birth_date->diffInHours($current_date)/24 : 0 ;
		$diff['m'] = !empty($diff['d'])? floor($diff['d']/12) : 0 ;
		$diff['y'] = !empty($diff['d'])? floor($diff['d']/365) : 0 ;
		$calculateAge = array(
				    'years'	=> $diff['y'],
				    'months'	=> $diff['m'],
				    'days'	=> $diff['d'],
				);
		$str = '';
		foreach($calculateAge as $key=>$val){
		    if($val){
			$str = $val .' ' . $key;
			break;
		    }
		}
		return $str .' old';
    }
    public static function getUserAccountById($id) {
		$data = self::getUserById($id);
		$students = students::where('user_id', $id)->first();
		if(!empty($students)){
		    $data->Bdate = date('d-m-Y',strtotime($students->birth_date));
		    $data->BdateAge = self::calculateAge($students->birth_date);
		}
		if(!empty($students->address_first_id)){
		    $addresses = addresses::find($students->address_first_id);
		    $data->address = $addresses->address_name;
		}
		$contacts = contacts::where('user_id', $id)->first();
		if(!empty($contacts)){
		    $data->phone = $contacts->value; 
		}
		
		if(!empty($students->ref_country_id)){
			$data->ref_country_id = $students->ref_country_id; 
			$data->refCountry = refCountry::find($students->ref_country_id);
		}
		return $data;
	
    }
    
    public static function getUserById($id) {
    	if($id){
	    $data = DB::table('users')->where('id', $id)->first();
	    $student = self::getStudent($data->id);
	    if($student){
		$data->Bdate = date('d-m-Y',strtotime($student->birth_date));
	    }
	    
	    return $data;
	}
    }
    
    public static function saveProfileData($profile) {
    	$user = self::getUser($profile['email']);
    	if(!empty($user)){
    		$data = $profile['data'];
	    	$user_exists_profile = DB::table('user_profile')->where('user_id', $user->id)->first(); 	
	    	if(!empty($user_exists_profile)){
				$result = DB::table('user_profile')->where('user_id', $user->id)->update([
				    	'school_name' 	=> $data['school_name'], 
				    	'profileimage' 	=> $data['profileimage'], 
				    	'info' 			=> $data['info'], 
				    	'slogan' 		=> $data['slogan'], 
				    	'address' 		=> $data['address'],
				    	'phone' 		=> $data['phone'], 
				    	'terms' 		=> $data['terms'], 
				    	'termsObj' 		=> serialize($data['termsObj']), 
				    	'updated_at' 	=> date('Y-m-d h:i:s'), 
				    ]);
			}else{
		    	$result = DB::table('user_profile')->insert([
				    	'user_id'		=> $user->id, 
				    	'school_name' 	=> $data['school_name'], 
				    	'profileimage' 	=> $data['profileimage'], 
				    	'info' 			=> $data['info'], 
				    	'slogan' 		=> $data['slogan'], 
				    	'address' 		=> $data['address'],
				    	'phone' 		=> $data['phone'], 
				    	'terms' 		=> $data['terms'], 
				    	'termsObj' 		=> serialize($data['termsObj']), 
				    	'updated_at' 	=> date('Y-m-d h:i:s'), 
				    ]);
			}
			if($result){
				return $user->id;
			}
		}
    	
    }
    
    /****** Update Profile Picture By current user login *******/
    public function updateProfilePicture($fileName) {
	$user_id = Auth::user()->id;
	if($user_id && Auth::user()->type == 'student'){
	    $User = User::find($user_id);
	    $User->img_profile_link = $fileName;
	    $User->save();
	    return true;
	}else{
	    $User = User::find($user_id);
	    $User->img_profile_link = $fileName;
	    $User->save();
	    if($User->id){
		Universities::where('user_id', $user_id)->update(['logo_link' => $fileName]);
	    }
	    return true;
	}
    }
    
    /****** Update password *******/
    public static function updatePasswordData($id, $password) {
    	if(!empty($id) && !empty($password)){
	    return DB::table('users')->where('id', $id)->update(['password' => $password]);
	}else{
	    return false;
	}
    }
    
    /****** Update Account Detail *******/
    public static function updateAccountData($id, $data) {	
	
		// Find User detail by user from users table
		$User = User::find($id);
		$User->firstname = $data['firstname'];
		$User->lastname  = $data['lastname'];
		$User->email 	 = $data['email'];
		$User->save();
		
		// Get student user Detail by user id..
		$students = students::where('user_id', $id)->first();
		if(empty($students->user_id)){
			$students = new students;
			$students->user_id 			= $id;
			$students->address_first_id 		= 0;
			$students->address_second_id 		= 0;
			$students->diploma_grade_id 		= 0;
			$students->ref_country_id 		= '';
			$students->birth_date 			= date('Y-m-d',strtotime($data['Bdate']));
			$students->profile_percent_complete 	= 0;
			$students->student_passport_id 		= 0;
			$students->save();		
		}
		
		// Upddate birthdate into student table...
		if(!empty($data['Bdate'])){
	   		students::where('user_id', $id)->update([
			    'birth_date' => date('Y-m-d',strtotime($data['Bdate']))
			]);
		}
		
		// Update reference Of country id into 
		if(!empty($data['ref_country_id'])){
			students::where('user_id', $id)->update([
				'ref_country_id' => $data['ref_country_id']
			]);
			$refCountry = refCountry::find($data['ref_country_id']);
		}
		
		// Upddate address into address table ...
		if(!empty($data['address'])){
		   	$addresses = new addresses;
			$profile_address =!empty($refCountry->ref_cntry_name)? $data['address'].' '.$refCountry->ref_cntry_name : $data['address'];
            $latlng = $addresses->getLatLng($profile_address);
                    
		    if(!empty($students->address_first_id)){
				addresses::where('id', $students->address_first_id)->update([
					'address_name' 	=> $data['address'],
					'lat'          => !empty($latlng['lat'])? $latlng['lat'] : '',
                    'lng'          => !empty($latlng['lng'])? $latlng['lng'] : '',
				]);
		    }else{
				// Save address into address table...
				$addresses = new addresses;
				$addresses->address_name = $data['address'];
				$addresses->lat = !empty($latlng['lat'])? $latlng['lat'] : '';
                $addresses->lng = !empty($latlng['lng'])? $latlng['lng'] : '';
				$addresses->save();
				
				// update address id into studetn table...
				students::where('user_id', $id)->update([
					    'address_first_id' => $addresses->id
					]);
		    }
		}
		
		//Get Contact by user id ...
		$contacts = contacts::where('user_id', $id)->first();
		if(!empty($data['phone'])){
		    if(!empty($contacts)){
		    	//Update contact into contact table....
				contacts::where('user_id', $id)->update([
				    'value' => $data['phone']
				]);
		    }else{
		    	//save contact into contact table....
				$contacts = new contacts;
				$contacts->user_id 		= $id;
				$contacts->ref_contact_type_id 	= 0;
				$contacts->value 		= $data['phone'];
				$contacts->save();
		    }
		}		
		
		// Return student user data...
		if(!empty($User)){
		    $userData = self::getUserById($id);
		    $userData->Bdate = $data['Bdate'];
		    $userData->address = $data['address'];
		    $userData->phone = $data['phone'];
		    $userData->ref_country_id = $data['ref_country_id'];
		    return $userData;
		}	    	
    }
    
    
    public static function getStudent($id) {
    	if($id)
    	return DB::table('students')->where('user_id', $id)->first(); 	
    }
    
    /**
     * @return  Illuminate\Database\Eloquent\Relations\HasMany
     */    
    public function messages(){
        return $this->hasMany('Message', 'user_sender_id');
    }  
    
}