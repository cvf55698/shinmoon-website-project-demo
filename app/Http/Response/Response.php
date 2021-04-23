<?php

namespace App\Http\Response;
	
class Response{
			
	public $is_view = false;
	public $view_path = "";
	public $view_param_mapping_arr = null;
	public $is_redirect = false;
	public $redirect_url = "";
	public $is_html = false;
	public $is_text = false;
	public $content_type = "";
	public $response_content = "";
	private $headers = [];
	public $error_message_arr=[];
	public $success_message_arr=[];
	public $warning_message_arr=[];

	public function set_error_message_arr($arr)
	{	
	}

	public function header($name,$value)
	{
		$this->headers[$name] = $value;
		return $this;
	}
		
	public function set_headers()
	{
		foreach($this->headers as $name=>$value){
			header("$name:$value");
		}
	}

}

?>
