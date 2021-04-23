<?php

namespace App\Http\Request;
	
use App\Http\Request\Request;
use App\Http\Request\RequestFile;

class RequestUtility{
		
	private static function handle_multiple_upload_files($file)
	{
		$file_ary = array();
		$file_count = count($file['name']);
		$file_key = array_keys($file);
		for($i=0;$i<$file_count;$i++){
			foreach($file_key as $val){
				$file_ary[$i][$val] = $file[$val][$i];
			}
		}

		return $file_ary;
	}
		
	private static function check_file_ok($file)
	{
		if($file['name']==''){return false;}
		if($file['error']>0){return false;}
		if($file['size'] < 0 || $file['size'] > MAX_FILE_LENGTH){return false;}
		
		return true;
	}

		
	private static function handle_text_input()
	{
		$text_params = [];
		$http_method = $_SERVER['REQUEST_METHOD'];
		$get_params = null;
		if($http_method=="GET"){
			$get_params = & $_GET;
		}else if($http_method=="POST"){
			$get_params = & $_POST;
		}else{
			throw new \Exception("[RequestUtility] http method not supported");
		}	
				
		foreach($get_params as $key=>$value){
			$text_params[$key] = $value;
		}

		return $text_params;
	}
		
	private static function handle_file_input()
	{
		$file_params = [];
		foreach($_FILES as $key=>$value){
			$type = gettype($value['name']);
			if($type=="string"){
				$upload_file = $value;
				if(static::check_file_ok($upload_file)){
					$file_params[$key] = new RequestFile($upload_file);
				}		

			}else{
				$upload_files_arr = static::handle_multiple_upload_files($value);
				$upload_files_len = count($upload_files_arr);
				$temp_file_arr = [];
				for($i=0;$i<$upload_files_len;++$i){
					$upload_file = 	$upload_files_arr[$i];	
					if(static::check_file_ok($upload_file)){
						array_push($temp_file_arr,new RequestFile($upload_file));
					}
				}

				if(count($temp_file_arr)!=0){
					$file_params[$key] = $temp_file_arr;
				}

			}
		}

		return $file_params;
	}
				
	public static function handle_request_headers()
	{
		$headers = [];
		if(function_exists('apache_request_headers')){
			$read_headers = apache_request_headers();
		}else{
			$read_headers = $_SERVER;
		}

		foreach($read_headers as $key=>$value){
			$headers[strtoupper($key)] = $value;
		}

		return $headers;
	}
		
	public static function request()
	{
		$text_params = static::handle_text_input();
		$file_params = static::handle_file_input();
		$headers = static::handle_request_headers();
		$request = new Request($text_params,$file_params,$headers);
		return $request;
	}

}

?>
