<?php

namespace App\Oauth;

class BaseProvider{

    protected $oauth_config;
    protected $oauth_website_key;
    protected $oauth_website_config;

    public function __construct($oauth_type_id)
    {
        $this->oauth_config = (require CONFIG_PATH."oauth.php");
        $this->oauth_website_key = $this->oauth_config['oauth_type_id'][$oauth_type_id];
        $this->oauth_website_config = $this->oauth_config['website'][$this->oauth_website_key];
    }
    
    public function get_auth_url()
    {
    }
    
    public static function get_oauth_site_config($member)
    {
        $member_oauth_type_id = $member['oauth_type'];
        $oauth_config = (require CONFIG_PATH."oauth.php");
        $oauth_config_site_key = $oauth_config['oauth_type_id'][$member_oauth_type_id];
        return $oauth_config['website'][$oauth_config_site_key];
    }

}

?>