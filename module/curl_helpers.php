<?php

use  App\Curl\CurlRequestUtility;

if(!function_exists('curl_get')){
    function curl_get($url,$params = [],$headers = [])
    {
        return CurlRequestUtility::get($url,$params,$headers);
    }
}

if(!function_exists('curl_post')){
    function curl_post($url,$params = [],$headers = [])
    {
        return CurlRequestUtility::post($url,$params,$headers);
    }
}

?>