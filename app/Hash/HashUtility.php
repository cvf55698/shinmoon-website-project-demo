<?php

namespace App\Hash;

class HashUtility{
    
    public static function bcrypt_hash($password)
    {
        return password_hash($password , PASSWORD_BCRYPT, ['cost' => 12,]);
    }

    public static function bcrypt_verify($password,$hash)
    {
        return ((bool)password_verify($password,$hash));
    }

    public static function html_encode($str)
    {
	    return htmlentities($str,ENT_QUOTES,"UTF-8");
    }

    public static function html_decode($str)
    {
	    return html_entity_decode($str,ENT_QUOTES,"UTF-8");
    }

}

?>