<?php

namespace App\Oauth;

use App\Oauth\BaseProvider;
use App\Database\DatabaseUtility;
use App\Result\ResultData;

class FacebookProvider extends BaseProvider{

    private $oauth_type_id = 1;

    public function __construct()
    {
        parent::__construct(1);
    }
    
    public function get_auth_url()
    {
        $client_id = $this->oauth_website_config['client_id'];
        $client_secret = $this->oauth_website_config['client_secret'];
        $redirect_uri = $this->oauth_website_config['redirect_uri'];
        $url = 'https://www.facebook.com/dialog/oauth?client_id='.$client_id
                .'&scope=email&redirect_uri='.$redirect_uri;
        return $url;
    }

    public function oauth_login($request)
    {
        $oauth_login_result = new ResultData();
        $code = $request->input("code");
        $facebook_oauth_config = $this->oauth_website_config;
        $get_token_url = $facebook_oauth_config ['token_uri'];
        $get_token_params = [
            'client_id'=>$facebook_oauth_config['client_id'],
	        'client_secret'=>$facebook_oauth_config['client_secret'],
	        'code'=>$code,
	        'redirect_uri'=>$facebook_oauth_config['redirect_uri'],
	        'scopes'=>$facebook_oauth_config['scopes'],
        ];

        try{
            $get_token_response = curl_get($get_token_url,$get_token_params);
            $token = json_decode($get_token_response,true)['access_token']; 
        }catch(\Exception | \Error $e){
            $oauth_login_result->setErrorMessage(['無法取得 token 參數']);
            return $oauth_login_result;
        }

        $get_me_url = $facebook_oauth_config ['me_uri'];
        $get_me_params = [
            'access_token'=>$token,
            'fields'=>$facebook_oauth_config['fields']
        ];
        
        try{
            $get_me_response = curl_get($get_me_url,$get_me_params);
            $me = json_decode($get_me_response,true);
        }catch(\Exception | \Error $e){
            $oauth_login_result->setErrorMessage(['無法取得 user profile']);
            return $oauth_login_result;
        }

        $oauth_id = (string) $me["id"];
        $oauth_email = (string) $me["email"];
        $fail = (($oauth_id == null) || ($oauth_id == '')) || (($oauth_email == null) || ($oauth_email == ''));
        if($fail){
            $oauth_login_result->setErrorMessage(['無法取得 user profile']);
            return $oauth_login_result;
        }

        $oauth_login_result->setSuccess(true);
        $oauth_login_result->setData( [
            'oauth_type_id'=>1,
            'oauth_id'=>$oauth_id,
            'email'=>$oauth_email,
        ]);

        return $oauth_login_result ;
    }

}

?>