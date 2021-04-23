<?php

namespace App\Regex;

use App\Http\Request\RequestUtility;
use App\Result\ResultData;

class RegexUtility{

    private static $type_arr = [
        'account'=>[
            'pattern'=>"/^[a-zA-Z0-9]{7,}$/",
            'error_message'=>'請輸入至少7個大小寫英文數字',
        ],
        'password'=>[
            'pattern'=>"/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{7,}$/",
            'error_message'=>'請輸入至少7個大小寫英文數字，需包含大小寫英文數字',
        ],
        'email' => [
            'pattern'=>'/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/',
            'error_message'=>'不符 Email 標準格式',
        ]
        ,'telephone_number'=>[
            'pattern'=>'/^09([0-9]{8})$/',
            'error_message'=>'不符電話標準格式',
        ]
    ];

    public static function check_pattern($check,$type='')
    {
        if(!array_key_exists($type,static::$type_arr)){
            return new ResultData(false);
        }

        return new ResultData((bool)preg_match(static::$type_arr[$type]['pattern'],$check));
    }

    public static function check_request_input_pattern($request,$check)
    {
        $check_result_arr = [];
        foreach($check as $col_key=>$value){
            $pattern_type = $value[0];
            if(!array_key_exists($pattern_type,static::$type_arr)){
                continue;
            }
            
            $col_name = $value[1];
            $col_value = $request->input($col_key);
            $pattern = static::$type_arr[$pattern_type]['pattern'];
            if(!preg_match($pattern,$col_value)){
                $error_message = "[$col_name] 欄位，".static::$type_arr[$pattern_type]['error_message'];
                array_push($check_result_arr,$error_message);
            }

        }

        if(count($check_result_arr)==0){
            return new ResultData(true);
        }else{
            return new ResultData(false,$check_result_arr);
        }
        
    }

}

?>