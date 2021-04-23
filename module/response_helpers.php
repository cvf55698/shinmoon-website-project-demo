<?php

use App\Http\Response\Response;

if(!function_exists('view')){
    function view($view_path,$user_params_arr = [],$error_message_arr = [],$success_message_arr = [],$warning_message_arr = [])
    {
        $response = new Response();
        $response->is_view = true;
        $response->view_path = $view_path;
        $len = count($url_params_name_arr);
        $view_param_mapping_arr = [];			
        foreach($user_params_arr as $param_name=>$param_value){
            $view_param_mapping_arr[$param_name] = $param_value;
        }
        
        $response->error_message_arr = $error_message_arr;
        $response->success_message_arr = $success_message_arr;
        $response->warning_message_arr = $warning_message_arr;
        $response->view_param_mapping_arr = $view_param_mapping_arr;		
        header('Content-Type:text/html; charset=UTF-8');
        return $response;
    }
}

if(!function_exists('json')){
    function json($json_data)
    {
        $response = new Response();			
        $response->content_type = "application/json";
        $response->response_content = json_encode($json_data,JSON_UNESCAPED_UNICODE);
        return $response;
    }
}

if(!function_exists('file_response')){
    function file_response($file_path,$download_file_name,$is_download,$file_type = "image")
    {
        $response = new Response();			
        $use_content_type = "application/octet-stream";
        $file_content = file_get_contents($file_path,true);
        if(!$is_download){
            if($file_type=="image"){
                $use_content_type = "image/jpg";
            }else if($file_type=="pdf"){
                $use_content_type = "application/pdf";
            }

        }else{
            $use_content_type = "application/force-download";
            $length = strlen($file_content);
            header("Content-length: ".$length);
            header('Content-Disposition: attachment; filename="'.$download_file_name.'"');
        }	
            
        $response->content_type = $use_content_type;
        $response->response_content = $file_content;
        return $response;
    }
}

if(!function_exists('redirect')){
    function redirect($url)
    {
        $response = new Response();
        $response->is_redirect = true;
        $response->redirect_url = $url;
        return $response;
    }
}

if(!function_exists('html')){
    function html($text)
    {
        $response = new Response();
        $response->is_html = true;
        $response->response_content = $text;
        return $response;
    }
}

if(!function_exists('text')){
    function text($text)
    {
        $response = new Response();
        $response->is_text = true;
        $response->response_content = $text;
        return $response;
    }
}

?>