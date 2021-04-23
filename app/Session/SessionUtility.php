<?php

namespace App\Session;

class SessionUtility{

    public static function has($key)
    {
        return ((bool)isset($_SESSION[$key]));   
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    public static function clear($key)
    {
        unset($_SESSION[$key]);
    }

    public static function init_flash()
    {
        if(!static::has("flash")){
            static::set("flash",['states'=>[],'values'=>[]]);
            return;
        }

        $flash_session_states = $_SESSION['flash']['states'];
        foreach($flash_session_states as $key=>$state_value){
            if($state_value==1){
                $_SESSION['flash']['states'][$key] = 2;
            }else {
                unset($_SESSION['flash']['states'][$key]);
                unset($_SESSION['flash']['values'][$key]);
            }

        }

    }

    public static function flash($key,$value=null)
    {
        $is_set_mode = (bool) ($value!=null);
        if($is_set_mode){
            if(!isset($_SESSION['flash']['states'][$key])){
                $_SESSION['flash']['states'][$key] = 1;
            }

            $_SESSION['flash']['values'][$key] = $value;
        }else{
            if(!isset($_SESSION['flash']['values'][$key])){
                return null;
            }

            return $_SESSION['flash']['values'][$key];
        }
    }

    public static function refresh()
    {
        $flash_session_states = $_SESSION['flash']['states'];
        foreach($flash_session_states as $key=>$state_value){
            $_SESSION['flash']['states'][$key] = 1;
        }
    }

}

?>