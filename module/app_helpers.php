<?php

if(!function_exists('load_const')){
    function load_const()
    {
        // HTTP METHOD
        define('HTTP_METHOD_GET',"GET");
        define('HTTP_METHOD_POST',"POST");

        define("MAX_FILE_LENGTH",1024 * 8000);
        define('BASEPATH', dirname(__FILE__)."/../");
        define('CONFIG_PATH', BASEPATH."config/");
        define('MODULE_PATH', BASEPATH."module/");
        define('STORAGE_PATH', BASEPATH."storage/");
        define('PUBLIC_PATH', BASEPATH."public/");
        define('VENDOR_PATH', BASEPATH."vendor/");
        define('VIEW_PATH', BASEPATH."views/");
        define('TEST_PATH', BASEPATH."tests/");
    }
}

if(!function_exists('load_app_config')){
    function load_app_config()
    {
        $app_config = require CONFIG_PATH."app.php";
        // APP_DEBUG
        $app_debug = isset($app_config["app_debug"]) ? $app_config["app_debug"] : false;
        define('APP_DEBUG', $app_debug);
        // Timezone
        $time_zone = isset($app_config["time_zone"]) ? $app_config["time_zone"] : "Asia/Taipei";
        date_default_timezone_set($time_zone);
    }
}

if(!function_exists('get_site_name')){
    function get_site_name()
    {
        $app_config = require CONFIG_PATH."app.php";
        return $app_config['site_name'];
    }
}

if(!function_exists('init_app')){
    function init_app()
    {
        load_const();
        load_env();
        load_app_config();
        check_php_environment();
        load_session_setting();
        dispatch_route();
    }
}

?>