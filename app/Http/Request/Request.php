<?php

namespace App\Http\Request;
	
use App\Http\Request\RequestFile;	

class Request{
	
	private $text_params ;
	private $file_params;
	private $headers;
		
	public function __construct($text_params,$file_params,$headers) 
	{
		$this->text_params = $text_params;
		$this->file_params = $file_params;
		$this->headers = $headers;
	}
		
	public function input($input_name)
	{
		if(array_key_exists($input_name,$this->text_params)){
			return $this->text_params[$input_name];
		}else{
			return null;
		}
	}

	public function file($input_name)
	{
		if(array_key_exists($input_name,$this->file_params)){
			return $this->file_params[$input_name];
		}else{
			return null;
		}
	}
		
	public function header($name)
	{
		if (!function_exists('apache_request_headers')){
			$name = 'HTTP_' . strtoupper(strreplace('-', '_', $name));
		}else{
			$name = strtoupper($name);
		}
		
		if(array_key_exists($name,$this->headers)){
			return $this->headers[$name];
		}else{
			return null;
		}
	}

}

?>
