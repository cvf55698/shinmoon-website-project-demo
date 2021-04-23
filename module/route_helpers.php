<?php

use App\Http\Response\ResponseUtility;
use App\Http\Request\RequestUtility;
use App\Http\Csrf\CsrfUtility;
use App\Http\Response\Response;

if(!function_exists('dispatch_route')){
    function dispatch_route()
    {
        $register_url = require CONFIG_PATH.'route.php';
        $check_url_ok = false;       
        $url = $_SERVER['REQUEST_URI'];
        $http_method = $_SERVER['REQUEST_METHOD'];
        $use_url_element = null;
        foreach($register_url as $url_element){
            $allow_http_method_array = $url_element["HTTP_METHOD"];
            if(!in_array($http_method,$allow_http_method_array)){
                continue;
            }
                
            $url_pattern = $url_element["URL_PATTERN"];		 
            if( ($find_question_mark_index = strpos($url,"?")) !== false  ){
                $url = substr($url,0,$find_question_mark_index);
            }

            if(!preg_match($url_pattern,$url,$url_pattern_match_arr)){
                continue;
            }

            array_splice($url_pattern_match_arr,0,1);
            $url_function_str = $url_element["URL_FUNCTION"];
            $class_function = explode ( "@" , $url_function_str);
            if(count($class_function)!=2){
                continue;
            }	

            $class_origin = $class_function[0];
            $class = str_replace(".","\\",$class_origin);
            $class = "App\\Http\\Controller\\".$class;
            $function = $class_function[1];
            if(!class_exists($class)){	
                continue;		
            }
        
            $controller_obj = new $class();
            if (!method_exists($controller_obj, $function)){
                continue;
            }
            
            $use_url_element = $url_element;
            $check_url_ok = true;
            break;
        }

        if(!$check_url_ok){
            header("HTTP/1.1 404 Not Found");
            exit(1);
        }

        try{
            $response = null;            
            if(array_key_exists('FILTER',$use_url_element)){
                $filters = $use_url_element['FILTER'];
                foreach($filters as $filter){
                    $filter_class_origin = str_replace(".","\\",$filter);
                    $filter_class = "App\\Http\\Filter\\".$filter_class_origin;
                    if(!class_exists($class)){	
                        continue;		
                    }

                    $response = (new $filter_class())->handle();
                    if($response===null){
                        continue;
                    }else{
                        break;
                    }

                }

            }
            
            if($response!=null){
                ResponseUtility::handle($response);	
                exit(0);
            }
            
            if(array_key_exists('CSRF',$use_url_element)){
                $csrf_type = $use_url_element['CSRF'];
                $request = RequestUtility::request();
                $csrf_token = $request->input('csrf_token');
                if($csrf_token===null){
                    header('HTTP/1.0 401 Unauthorized');
                    exit(1);
                }
                
                if(!CsrfUtility::verify_hash($csrf_type,$csrf_token)){
                    header('HTTP/1.0 401 Unauthorized');
                    exit(1);
                }
                
            }
            
            if($response===null){
                $response = $controller_obj->$function(...$url_pattern_match_arr);
            }
        
            if(is_string($response)===true){
                $response = html($response);
            }
            
            $response->header("x-frame-options","DENY");
            $response->header("x-xss-Protection","1; mode=block");
            ResponseUtility::handle($response);	
        }catch(\Exception | \Error $e){
            if(APP_DEBUG){
                $error_arr = [];
                array_push($error_arr,"[Error] : ".$e->getMessage());
                include VIEW_PATH."error/show_php_enviroment_check_error_log.php";		
                exit(0);
            }else{
                header("HTTP/1.1 500 Internal Server Error");
            }
        }
        
    }
}

?>