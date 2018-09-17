<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\DocumentTypes;
class StudentDocuments extends Model
{
    protected $table = 'student_documents';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    
    /**
     * Upload Basic Document by student user...
     * @param $fileName, $application_id, $diploma_requirement_id, $document_type_id
     * 
     * @return Mixed
     */
    public function uploadBasicDocument($fileName, $fileType)
    {
    	$student_id = Auth::user()->id;
    	$documentType = DocumentTypes::Where('name',$fileType)->first();
        
        // Save Student Upload Basic document
        $this->student_id = $student_id;
        $this->document_type_id = !empty($documentType->id) ? $documentType->id : 0;
        $this->name = 'Basic Document';
        $this->link = $fileName;
        $this->save();
        return $this->id;
    }
    
    /**
     * Get Basic Document by student user...
     * @param 
     * 
     * @return Mixed
     */
    public function getBasicDocuments()
    {
    	$student_id = Auth::user()->id;
    	$documents =  $this->where(array('student_id' => $student_id, 'name' => 'Basic Document'))->get();
    	if(!empty($documents)){
			foreach($documents as $key=>$val){
				if($val->document_type_id){
					$documents[$key]->document_type = DocumentTypes::find($val->document_type_id);
				}
			}
		}
		return $documents;
	}
    
}
