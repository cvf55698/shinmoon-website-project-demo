<?php

namespace App\Shinmoon\Member;

use App\Auth\MemberAuth;
use App\Oauth\BaseProvider;

class MemberUtility{

    public static function check_select_member_is_oauth($member) : bool
    {
        return (((int)$member['oauth_type'])!=0);
    }

    public static function get_login_member_id()
    {
        return (int) MemberAuth::member()['id'];
    }

    public static function get_login_member_email()
    {
        return MemberAuth::member()['email'];
    }

    public static function get_select_member_id($member)
    {
        return (int) $member['id'];
    }

    public static function get_select_member_email($member)
    {
        return $member['email'];
    }

    public static function get_select_member_oauth_site_name($member)
    {
        if(static::check_select_member_is_oauth($member)){
            $oauth_site_config = BaseProvider::get_oauth_site_config($member);
            $oauth_config_site_name = $oauth_site_config['site_name'];
            return $oauth_config_site_name;
        }else{
            return '';
        }
    }

}

?>