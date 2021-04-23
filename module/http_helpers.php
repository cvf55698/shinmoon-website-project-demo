<?php

use App\Http\Request\RequestUtility;

if(!function_exists('request')){
    function request(){
        return RequestUtility::request();
    }
}

?>