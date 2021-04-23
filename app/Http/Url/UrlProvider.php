<?php

namespace App\Http\Url;

class UrlProvider{

    public static function get_base_url()
    {
        $app_config = require CONFIG_PATH."app.php";
        $url = $app_config['base_site_url'];
        $len = mb_strlen($url);
        $url_suffix = substr($url,$len-1,1);
        return ($url_suffix == '/') ? $url : ($url."/");
    }

}

?>