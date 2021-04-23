<?php

namespace App\Http\Request;
	
class RequestFile{

	private $file;
	private $filename;
	private $size;

	public function __construct($file) 
	{
		$this->file = $file;
		$this->filename = $file['name'];
		$this->size = $file['size'];
	}
	
	public function get_filename()
	{
		return $this->filename;
	}
		
	public function move($upload_file_path)
	{
		$upload_result = array('success'=>false,"error_code"=>0);
		if( ($this->file===null) || (!isset($this->file))  ){
			$upload_result['error_code']=1;
			return $upload_result;
		}

		if(is_uploaded_file($this->file['tmp_name'])){	
			if(!move_uploaded_file($this->file['tmp_name'],$upload_file_path)){
				$upload_result['error_code']=2;
			}else{
				$upload_result["success"] = true;
			}
			
		}else{
			$upload_result['error_code']=3;
		}

		return $upload_result;
	}
	
}

?>
