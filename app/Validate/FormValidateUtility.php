<?php

namespace App\Validate;

use App\Http\Request\RequestUtility;
use App\Regex\RegexUtility;
use App\Result\ResultData;

class FormValidateUtility{

    public static function check_require_param($request,$text_params,$file_params = [])
    {
        $error_messages_arr = [];
        foreach($text_params as $text_param=>$name){
            $text = $request->input($text_param);
            if($text===null || $text==''){
                array_push($error_messages_arr,"未填寫 [$name] 欄位");
            }
        }

        foreach($file_params as $file_param=>$name){
            $file = $request->file($file_param);
            if($file===null){
                array_push($error_messages_arr,"未上傳 [$name] 欄位檔案");
            }
        }

        if(count($error_messages_arr)==0){
            return new ResultData(true);
        }else{
            return new ResultData(false,$error_messages_arr);
        }
        
    }


    public static function check_password_same($request,$text_params)
    {
        $value = null;
        foreach($text_params as $text_param){
            $text = $request->input($text_param);
            if($value===null){
                $value = $text;
            }else{
                if($value!=$text){
                    return new ResultData(false);
                }
            }
        }

        return new ResultData(true);
    }

    public static function check_pattern($check,$type='')
    {
        return RegexUtility::check_pattern($check,$type);
    }

    public static function check_request_input_pattern($request,$check)
    {
        return RegexUtility::check_request_input_pattern($request,$check);
    }

}

?>