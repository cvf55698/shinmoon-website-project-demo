<?php

use App\Session\SessionUtility;

if(!function_exists('load_session_setting')){
    function load_session_setting()
    {
        $session_config = require CONFIG_PATH.'session.php';
        $session_lifetime = isset($session_config['session_lifetime'])?$session_config['session_lifetime']:1440;
        ini_set('session.gc_maxlifetime', $session_lifetime); 
        ini_set("session.cookie_lifetime",$session_lifetime);
        ini_set("session.gc_probability","1");
        ini_set("session.gc_divisor","1");
        $secure = isset($session_config["secure"])?$session_config["secure"]:false;
        $httponly = isset($session_config["httponly"])?$session_config["httponly"]:false;
        session_set_cookie_params($session_lifetime,"/",null,$secure,$httponly);
        $session_name = $session_config['session_name'];
        if(isset($session_name)){
            session_name($session_name);
        }
            
        if($session_config['use_redis']){
            ini_set("session.save_handler","redis");
            ini_set('session.save_path',$session_config['redis_session_save_path']);   
        }

        session_start();
        SessionUtility::init_flash();
    }
}

?>