<?php

namespace App\Oauth;

use App\Oauth\BaseProvider;
use App\Database\DatabaseUtility;
use App\Result\ResultData;

class GoogleProvider extends BaseProvider{

    private $oauth_type_id = 2;

    public function __construct()
    {
        parent::__construct(2);
    }

    public function get_auth_url()
    {
        $client_id = $this->oauth_website_config['client_id'];
        $client_secret = $this->oauth_website_config['client_secret'];
        $redirect_uri = $this->oauth_website_config['redirect_uri'];
        $url = 'https://accounts.google.com/o/oauth2/auth?scope='
                .'https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&state=&'
                .'redirect_uri='.$redirect_uri.'&response_type=code&client_id='.$client_id.'&include_granted_scopes=true&approval_prompt=force';
        return $url;
    }

    public function oauth_login($request)
    {
        $oauth_login_result = new ResultData();
        $code = $request->input("code");
        $google_oauth_config = $this->oauth_website_config;
        $get_token_url = $google_oauth_config ['token_uri'];
        $get_token_params = [
            'client_id'=>$google_oauth_config['client_id'],
	        'client_secret'=>$google_oauth_config['client_secret'],
	        'code'=>$code,
	        'redirect_uri'=>$google_oauth_config['redirect_uri'],
	        'grant_type'=>'authorization_code',
        ];

        try{
            $get_token_response = curl_post($get_token_url,$get_token_params);
            $get_token = json_decode($get_token_response,true);
            $access_token = $get_token['access_token'];
            $id_token = $get_token['id_token'];
        }catch(\Exception | \Error $e){
            $oauth_login_result->setErrorMessage(['無法取得 token 參數']);
            return $oauth_login_result;
        }

        $get_me_url = $google_oauth_config['me_uri'];
        $get_me_params = ['accessToken'=>$access_token,];
        try{
            $get_me_response = curl_post($get_me_url,$get_me_params);
            $me = json_decode($get_me_response,true);
        }catch(\Exception | \Error $e){
            $oauth_login_result->setErrorMessage(['無法取得 user profile']);
            return $oauth_login_result;
        }

        $oauth_id = (string) $me["user_id"];
        $oauth_email = (string) $me["email"];
        $fail = (($oauth_id == null) || ($oauth_id == '')) || (($oauth_email == null) || ($oauth_email == ''));
        if($fail){
            $oauth_login_result->setErrorMessage(['無法取得 user profile']);
            return $oauth_login_result;
        }

        $oauth_login_result->setSuccess(true);
        $oauth_login_result->setData( [
            'oauth_type_id'=>2,
            'oauth_id'=>$oauth_id,
            'email'=>$oauth_email,
        ]);
        return $oauth_login_result ;
    }
    
}

?>