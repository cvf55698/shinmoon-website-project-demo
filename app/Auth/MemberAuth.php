<?php

namespace App\Auth;

use App\Session\SessionUtility;
use App\Oauth\BaseProvider;

class MemberAuth{

    public static function check()
    {
        return SessionUtility::has('member');
    }
    
    public static function is_oauth()
    {
        $member = MemberAuth::member();
        return  ((bool)($member["oauth_type"]!=0));
    }

    public static function member()
    {
        return SessionUtility::get('member');
    }

    public static function set_member($member)
    {
        SessionUtility::set('member',$member);
    }

    public static function logout()
    {
        SessionUtility::clear('member');
    }

    public static function get_member_oauth_site_name()
    {
        if(MemberAuth::is_oauth()){
            $member = MemberAuth::member();
            $oauth_site_config = BaseProvider::get_oauth_site_config($member);
            $oauth_config_site_name = $oauth_site_config['site_name'];
            return $oauth_config_site_name;
        }else{
            return '';
        }
    }
}

?>