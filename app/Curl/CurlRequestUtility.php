<?php

namespace App\Curl;

class CurlRequestUtility{

	public static function get($url,$params = [],$headers = [])
	{
		$param_processed_arr = [];
		foreach($params as $key=>$value){
			$str = "${key}=${value}";
			array_push($param_processed_arr,$str);
		}
			
		$url = $url."?".implode('&', $param_processed_arr);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);					
		$output = curl_exec($ch);							
		$return_output = "";
		if($output){
			$return_output = $output;
    	}

		curl_close($ch);
        return $return_output;
    }

	public static function post($url,$params = [],$headers = [])
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');
		$output = curl_exec($ch);
		curl_close($ch);
        return $output;
	}

}

?>