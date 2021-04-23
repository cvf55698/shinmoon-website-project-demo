<?php

namespace App\Http\Csrf;

use App\Shinmoon\Member\MemberUtility;

class CsrfUtility{

    private static $token_type_arr = null;

    private static function get_token_type_function_arr()
    {
        if(self::$token_type_arr == null){
            self::$token_type_arr = [];
            self::$token_type_arr['member'] = function($app_key,$options = []){
                $member_id = isset($options['member_id']) ? ((int) $options['member_id']) : MemberUtility::get_login_member_id();
                return $app_key.'-member-'.$member_id;
            };
            self::$token_type_arr['member_register'] = function($app_key,$options = []){
                return $app_key.'-member_register-';
            };
            self::$token_type_arr['member_login'] = function($app_key,$options = []){
                return $app_key.'-member_login-';
            };
            self::$token_type_arr['member_send_new_password_email'] = function($app_key,$options = []){
                return $app_key.'-member_send_new_password_email-'.date('Y-m-d H:i:s:u');
            };
            self::$token_type_arr['member_send_new_password_email_page'] = function($app_key,$options = []){
                return $app_key.'-member_send_new_password_email-';
            };
            self::$token_type_arr['member_send_new_password_page'] = function($app_key,$options = []){
                return $app_key.'-member_send_new_password-';
            };
            self::$token_type_arr['member_operate_cart_product'] = function($app_key,$options = []){
                $member_id = isset($options['member_id']) ? ((int) $options['member_id']) : MemberUtility::get_login_member_id();
                return $app_key.'-member-['.$member_id.']-operate-cart-product-'.$options['product_id'];
            };
        }

        return self::$token_type_arr;
    }
    
    private static function get_token_pattern($type,$app_key,$options = [])
    {
        $token_type_arr = static::get_token_type_function_arr();
        if(!array_key_exists($type,$token_type_arr ) ){
            return '';
        }

        return call_user_func($token_type_arr[$type],$app_key,$options);
    }

    public static function generate_token($type,$options = [])
    {
        $app_key = (require CONFIG_PATH."app.php")['app_key'];
        $password = static::get_token_pattern($type,$app_key,$options);
        if($password == ''){
            return '';
        }

        $hash_options = ['cost' => 12, ];
        $hash = password_hash($password , PASSWORD_BCRYPT, $hash_options);
        return $hash;
    }

    public static function verify_hash($type,$hash,$options = [])
    {
        $app_key = (require CONFIG_PATH."app.php")['app_key'];
        $password = static::get_token_pattern($type,$app_key,$options);
        if($password == ''){
            return false;
        }

        return ((bool)password_verify($password,$hash ));
    }

}

?>