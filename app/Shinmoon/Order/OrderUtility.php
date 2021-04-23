<?php

namespace App\Shinmoon\Order;

use App\Hash\HashUtility;
use App\Result\ResultData;
use App\Validate\FormValidateUtility;

class OrderUtility{

    public static function get_invoice_info($order)
    {
        $invoice_info = "";
        $invoice_type = (int) $order['invoice_type'];
        if($invoice_type == 1){
            $invoice_info = "電子發票(B2C) 發票載具 : ezPay 電子發票平台載具";
        }else if($invoice_type == 2){
            $invoice_info =  "電子發票(B2C) 發票載具<br>手機載具條碼 : ".$order['member_carrier'];
        }else if($invoice_type == 3){
            $svg_id = $order['svg_id'];
            $svg_name = $order['svg_name'];
            if(($svg_name=='') || ($svg_name==null)){
                $invoice_info =  "發票捐贈<br>社福團體愛心碼：$svg_id";
            }else{
                $invoice_info =  "發票捐贈 : $svg_name";
            }

        }

        return $invoice_info;
    }

    public static function check_order_row_form_correct($order) :bool
    {
        $param_arr = [];
        $invoice_type = (int) $order['invoice_type'];
        $param_arr['name'] = (string) $order['recipient_name'];
        $param_arr['telephone_number'] = (string) $order['recipient_telephone_number'];
        if($invoice_type==1){
            $param_arr['invoice_type'] = 'second_invoice';
            $param_arr['second_invoice_type'] = '1';
        }else if($invoice_type == 2){ 
            $param_arr['invoice_type'] = 'second_invoice';
            $param_arr['second_invoice_type'] = '2';
            $param_arr['member_carrier'] = $order['member_carrier'];

        }else if($invoice_type == 3){
            $param_arr['invoice_type'] = 'donate';
            $param_arr['donate_invoice_type'] = '2';
            $param_arr['other_donate'] = $order['svg_id'];
        }else{
            return false;
        }

        return (bool) OrderUtility::order_setting_param_check($param_arr)->getSuccess();
    }

    public static function get_order_setting_param_arr_from_request($request)
    {
        $params = ['name','telephone_number','invoice_type','donate_invoice_type','choose_donate','other_donate','second_invoice_type','member_carrier'];
        $param_arr = [];
        foreach($params as $param){
            $param_value = $request->input($param);        
            if($param_value==null){
                $param_value = '';
            }else{
                $param_value = trim(HashUtility::html_encode($param_value));
            }

            if($param=='choose_donate'){
                $str_arr = explode(":",$param_value);
                if(count($str_arr)==2){
                    $param_value = $str_arr[0];
                }else{
                    $param_value = '';
                }
            }

            $param_arr[$param] = $param_value;
        }

        return $param_arr;
    }

    public static function order_setting_param_check($param_arr)
    {
        $utility_result = new ResultData();
        foreach($param_arr as $param_name=>$param_value){
            $$param_name = $param_value;
        }

        if($name==''){
            $utility_result->setErrorMessage(['請填寫 收件人姓名']);
            return $utility_result;
        }else if(mb_strlen($name)>10){
            $utility_result->setErrorMessage(['收件人姓名 請填寫小於 10 個字元']);
            return $utility_result;
        }

        if($telephone_number==''){
            $utility_result->setErrorMessage(['請填寫 收件人電話']);
            return $utility_result;
        }else if(!FormValidateUtility::check_pattern($telephone_number,'telephone_number')->getSuccess()){
            $utility_result->setErrorMessage(['收件人電話 格式錯誤']);
            return $utility_result;
        }

        if($invoice_type == 'donate'){
            if($donate_invoice_type == '1'){
                if($choose_donate == ''){
                    $utility_result->setErrorMessage(['請選擇 捐贈單位']);
                    return $utility_result;
                }else if(!preg_match("/^[0-9]{1,}$/",$choose_donate)){
                    $utility_result->setErrorMessage(['選擇 捐贈單位 欄位 格式錯誤']);
                    return $utility_result;
                }else if(mb_strlen($choose_donate)>10){
                    $utility_result->setErrorMessage(['選擇 捐贈單位 欄位 長度 請勿超過10個字元']);
                    return $utility_result;
                }

            }else if($donate_invoice_type == '2'){
                if($other_donate==''){
                    $utility_result->setErrorMessage(['請填寫 其他社福團體 欄位']);
                    return $utility_result;
                }else if(!preg_match("/^[0-9]{1,}$/",$other_donate)){
                    $utility_result->setErrorMessage(['其他社福團體 欄位 格式錯誤']);
                    return $utility_result;
                }else if(mb_strlen($other_donate)>10){
                    $utility_result->setErrorMessage(['其他社福團體 欄位 長度 請勿超過10個字元']);
                    return $utility_result;
                }

            }else{
                $utility_result->setErrorMessage(['捐贈發票 參數錯誤']);
                return $utility_result;
            }

        }else if($invoice_type == 'second_invoice'){
            if($second_invoice_type == '1'){

            }else if($second_invoice_type == '2'){
                if($member_carrier == ''){
                    $utility_result->setErrorMessage(['請填寫 手機條碼載具 欄位']);
                    return $utility_result;
                }else if(!preg_match("/^[0-9A-Z]{1,}$/",$member_carrier)){
                    $utility_result->setErrorMessage(['手機條碼載具 欄位 格式錯誤']);
                    return $utility_result;
                }else if(mb_strlen($member_carrier)>20){
                    $utility_result->setErrorMessage(['手機條碼載具 長度 請勿超過20個字元']);
                    return $utility_result;
                }

            }else{
                $utility_result->setErrorMessage(['載具類型 參數錯誤']);
                return $utility_result;
            }

        }else{
            $utility_result->setErrorMessage(['發票類型 參數錯誤']);
            return $utility_result;
        }

        $utility_result->setSuccess(true);
        return $utility_result;
    }

    public static function get_update_order_setting_cluase_arr_from_param_arr($param_arr)
    {
        foreach($param_arr as $param_name=>$param_value){
            $$param_name = $param_value;
        }

        $set_param_arr = [];
        array_push($set_param_arr,"recipient_name = '$name'");
        array_push($set_param_arr,"recipient_telephone_number = '$telephone_number'");
        if($invoice_type == 'donate'){
            array_push($set_param_arr,"invoice_type = 3");
            if($donate_invoice_type == '1'){
                array_push($set_param_arr,"svg_id = '$choose_donate'");
            }else if($donate_invoice_type == '2'){
                array_push($set_param_arr,"svg_id = '$other_donate'");
            }else{
                throw new \Exception();
            }

            array_push($set_param_arr,"member_carrier = ''");
        }else if($invoice_type == 'second_invoice'){
            if($second_invoice_type == '1'){
                array_push($set_param_arr,"invoice_type = 1");
                array_push($set_param_arr,"member_carrier = ''");
            }else if($second_invoice_type == '2'){
                array_push($set_param_arr,"invoice_type = 2");
                array_push($set_param_arr,"member_carrier = '$member_carrier'");
            }else{
                throw new \Exception();
            }

            array_push($set_param_arr,"svg_id = ''");
        }else{
            throw new \Exception();
        }
        
        return $set_param_arr;
    }

}

?>