<?php

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Validate\FormValidateUtility;
use App\Auth\MemberAuth;
use App\Shinmoon\Traits\ControllerTrait;
use App\Oauth\FacebookProvider;
use App\Oauth\GoogleProvider;

class OauthController{

    use ControllerTrait;

    private function oauth_callback($oauth_provider)
    {
        $request = request();
        $oauth_login_result = $oauth_provider->oauth_login($request);
        if(!$oauth_login_result->getSuccess()){
            SessionUtility::flash("oauth_login_fail",$oauth_login_result->getErrorMessage());
            return redirect("/login");
        }
        
        $member_data = $oauth_login_result->getData();
        $service_result = $this->memberService->oauth_login($member_data);
        if($service_result->getSuccess()){
            SessionUtility::flash("login_success_message",["登入成功"]);
            return redirect("/");
        }else{
            SessionUtility::flash("oauth_login_fail",$service_result->getErrorMessage());
            return redirect("/login");
        }
    }

    public function facebook_callback()
    {
        $facebook_provider = new FacebookProvider();
        return $this->oauth_callback($facebook_provider);
    }

    public function google_callback()
    {
        $google_provider = new GoogleProvider();
        return OauthController::oauth_callback($google_provider);
    }

}

?>