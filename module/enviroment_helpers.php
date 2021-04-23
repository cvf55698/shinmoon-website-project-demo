<?php

use App\Enviroment\PhpVersionUtility;
use App\Database\DatabaseUtility;

if(!function_exists('check_php_environment')){
    function check_php_environment()
    {
        $error_arr = [];
        $check_php_version_result = PhpVersionUtility::compare_php_version("7.0.0");
        if($check_php_version_result===null){
            array_push($error_arr,"Can not check php version by PHP_VERSION_ID contant");
        }else{
            if($check_php_version_result===false){
                array_push($error_arr,"Site Application only allow php version at least 7.0.0");
            }
        }
        
        if(class_exists("mysqli")===false){
            array_push($error_arr,"Require [mysqli] extension");
        }
        
        $session_config = require CONFIG_PATH."session.php";
        if($session_config["use_redis"]===true){
            if(class_exists("redis")===false){
                array_push($error_arr,"Require [redis] extension , or set 'use_redis' to 'false' in /config/session.php");
            }
        }
        
        $mail_need_extensions= ["ctype","filter","hash"];
        foreach($mail_need_extensions as $mail_need_extension){
            if(extension_loaded($mail_need_extension)===false){
                array_push($error_arr,"Require [ctype] [filter] [hash] extension , since site application use mail function");
                break;
            }
        }

        if(class_exists("PHPMailer\PHPMailer\PHPMailer")===false){
            array_push($error_arr,"Require [PHPMailer] package , please install it via the command 'composer require phpmailer/phpmailer'");
        }
        
        if(class_exists("\Smarty")===false){
            array_push($error_arr,"Require [Smarty] package , please install it via the command 'composer require smarty/smarty'");
        }
        
        if(class_exists("\PDO")===false){
            array_push($error_arr,"Please Check [PDO] support");
        }

        try{
            $db = DatabaseUtility::getInstance();
            $select_innodb_sql = "select ENGINE from information_schema.ENGINES "
                ."where ENGINE='InnoDB' and SUPPORT!='NO' and TRANSACTIONS='YES' and XA='YES' and SAVEPOINTS='YES';";
            $select_innodb_result = $db->query($select_innodb_sql);
            if($select_innodb_result->getData()['row_count']==0){
                array_push($error_arr,'Please check mysql support InnoDB , as long as the functions [TRANSACTIONS] [XA] [SAVEPOINTS]');
            }

            $select_utf8mb4_charset_sql = "select CHARACTER_SET_NAME from information_schema.CHARACTER_SETS where  CHARACTER_SET_NAME = 'utf8mb4';";
            $select_utf8mb4_charset_result = $db->query($select_utf8mb4_charset_sql);
            if($select_utf8mb4_charset_result->getData()['row_count']==0){
                array_push($error_arr,'Please check mysql support [utf8mb4] charset');
            }

            $select_utf8mb4_collation_sql = "select COLLATION_NAME from information_schema.COLLATIONS where COLLATION_NAME = 'utf8mb4_unicode_ci';";
            $select_utf8mb4_collation_result = $db->query($select_utf8mb4_collation_sql);
            if($select_utf8mb4_collation_result->getData()['row_count']==0){
                array_push($error_arr,'Please check mysql support [utf8mb4_unicode_ci] collation');
            }

        }catch(\Exception | \Error $e){
            array_push($error_arr,"Please check [PDO] [pdo_mysql] support , or database connections setting. ");
        }
        
        if(count($error_arr)!=0){
            include VIEW_PATH."error/show_php_enviroment_check_error_log.php";
            exit();
        }
        
    }
}

?>