<?php

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Validate\FormValidateUtility;
use App\Auth\MemberAuth;
use App\Shinmoon\Traits\ControllerTrait;

class MemberController{
    
    use ControllerTrait;

    public function register_page()
    {
        $error_message = [];
        if(SessionUtility::flash("register_error_message")){
            $error_message = SessionUtility::flash("register_error_message");
        }

        return view('shop/member/register-page',[],$error_message);
    }

    public function register()
    {
        $request = request();
        $require_text_params = ['account'=>'帳號','email'=>'Email','password'=>'密碼','repassword'=>'密碼確認',];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/register-page',[], $check_require_param_result->getErrorMessage());
        }

        if(!FormValidateUtility::check_password_same($request,['password','repassword'])->getSuccess()){
            return view('shop/member/register-page',[],['[密碼] [密碼確認] 兩個欄位請填寫相同值']);
        }

        $check_pattern_text_params = ['account'=>['account','帳號'],'email'=>['email','Email'],'password'=>['password','密碼'],];
        $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
        if(!$check_pattern_result->getSuccess()){
            return view('shop/member/register-page',[],$check_pattern_result->getErrorMessage());
        }

        if(mb_strlen($request->input('account'))>=30){
            return view('shop/member/register-page',[],['[帳號] 請輸入小於30個字元']);
        }

        if(mb_strlen($request->input('email'))>=30){
            return view('shop/member/register-page',[],['[Email] 請輸入小於30個字元']);
        }

        $member_data = ['account'=>$request->input('account'),'password'=>$request->input('password'),'email'=>$request->input('email')];
        $service_result = $this->memberService->register($member_data);
        if($service_result->getSuccess()){
            SessionUtility::flash("register_success_message",["註冊成功，請至 Email 收信，點擊帳號啟用連結"]);
	        return redirect("/login");
        }else{
            SessionUtility::flash("register_error_message",$service_result->getErrorMessage());
            return redirect("/register");
        }
    }

    public function login_page()
    {
        $success_message = [];
        if(SessionUtility::flash("register_success_message")){
            $success_message = SessionUtility::flash("register_success_message");
        }else if(SessionUtility::flash("reset_password_success_message")){
            $success_message = SessionUtility::flash("reset_password_success_message");
        }else if(SessionUtility::flash("forget_password_success_message")){
            $success_message = SessionUtility::flash("forget_password_success_message");
        }

        $error_message = [];
        if(SessionUtility::flash("oauth_login_fail")){
            $error_message  = SessionUtility::flash("oauth_login_fail");
        }else if(SessionUtility::flash("member_not_exist")){
            $error_message  = SessionUtility::flash("member_not_exist");
        }

        return view('shop/member/login-page',[],$error_message,$success_message);
    }

    public function login()
    {
        $request = request();
        $require_text_params = ['account_or_email'=>'帳號 或 Email','password'=>'密碼',];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/login-page',[], $check_require_param_result->getErrorMessage());
        }

        $member_data = ['account_or_email'=>$request->input('account_or_email'),'password'=>$request->input('password')];
        $service_result = $this->memberService->web_login($member_data);
        if($service_result->getSuccess()){
            SessionUtility::flash("login_success_message",["登入成功"]);
            return redirect("/");
        }else{
            return view('shop/member/login-page',[],$service_result->getErrorMessage());
        }
    }

    public function logout()
    {
        $this->memberService->logout();
        SessionUtility::flash("logout_success_message",['登出成功']);
        return redirect("/");
    }

    public function update_password_page()
    {
        if(MemberAuth::is_oauth()){
            $oauth_config_site_name = MemberAuth::get_member_oauth_site_name();
            return view('shop/member/password-edit-page',['is_oauth'=>true,],["利用 $oauth_config_site_name 社群網站登入的帳號，無須修改密碼"]);
        }

        return view('shop/member/password-edit-page',[]);
    }

    public function update_password()
    {
        $request = request();
        if(MemberAuth::is_oauth()){
            $oauth_config_site_name = MemberAuth::get_member_oauth_site_name();
            return view('shop/memberpassword-edit-page',['is_oauth'=>true,],["利用 $oauth_config_site_name 社群網站登入的帳號，無須修改密碼"]);
        }

        $require_text_params = [ 'origin_password'=>'目前密碼','new_password'=>'新密碼','re_new_password'=>'新密碼確認'];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/password-edit-page',[], $check_require_param_result->getErrorMessage());
        }

        $member_data = ['origin_password'=>$request->input('origin_password'),'new_password'=>$request->input('new_password')
            ,'re_new_password'=>$request->input('re_new_password'),];

        if(!FormValidateUtility::check_password_same($request,['new_password','re_new_password'])->getSuccess()){
            return view('shop/member/password-edit-page',[],['[新密碼] [新密碼確認] 兩個欄位請填寫相同值']);
        }

        $check_pattern_text_params = ['new_password'=>['password','新密碼'], ];
        $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
        if(!$check_pattern_result->getSuccess()){
            return view('shop/member/password-edit-page',[],$check_pattern_result->getErrorMessage());
        }

        if($member_data['origin_password']==$member_data['new_password']){
            return view('shop/member/password-edit-page',[],['[目前密碼] [新密碼] 兩個欄位請填寫不同值']);
        }

        $service_result = $this->memberService->edit_password($member_data,MemberAuth::is_oauth());
        if($service_result ->getSuccess()){
            MemberAuth::logout();
            SessionUtility::flash("reset_password_success_message",['已成功修改密碼，請重新登入']);
		    return redirect("/login");
        }else{
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            return view('shop/member/password-edit-page',[],$service_result->getErrorMessage());
        }
    }

    public function update_email_page()
    {
        $success_message = [];
        if(SessionUtility::flash("email_update_success")){
            $success_message = SessionUtility::flash("email_update_success");
        }

        return view('shop/member/email-edit-page',[],[],$success_message);
    }

    public function update_email()
    {
        $request = request();
        $require_text_params = ['email'=>'Email',];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/email-edit-page',[], $check_require_param_result->getErrorMessage());
        }

        $check_pattern_text_params = ['email'=>['email','Email'],];
        $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
        if(!$check_pattern_result->getSuccess()){
            return view('shop/member/email-edit-page',[],$check_pattern_result->getErrorMessage());
        }

        $member_data = ['email'=>$request->input('email'),];
        $service_result = $this->memberService->edit_email($member_data);
        if($service_result ->getSuccess()){
            SessionUtility::flash("email_update_success",['已成功更新會員 Email，會員可至 Email 信箱查看通知']);
            return redirect("/email/edit");
        }else{
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            return view('shop/member/email-edit-page',[],$service_result->getErrorMessage());
        }
    }

    public function forget_password_email_page()
    {
        return view('shop/member/forget-password-send-email-page',[]);
    }

    public function forget_password_email()
    {
        $request = request();
        $require_text_params = ['email'=>'Email',];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/forget-password-send-email-page',[], $check_require_param_result->getErrorMessage());
        }

        $check_pattern_text_params = ['email'=>['email','Email'],];
        $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
        if(!$check_pattern_result->getSuccess()){
            return view('shop/member/forget-password-send-email-page',[],$check_pattern_result->getErrorMessage());
        }

        $member_data = ['email'=>$request->input('email'),];
        $service_result = $this->memberService->send_reset_password_email($member_data);
        if($service_result ->getSuccess()){
            return view('shop/member/forget-password-send-email-page',[],[],['已成功寄送重設密碼確認信，請至 Email 信箱確認']);
        }else{
            return view('shop/member/forget-password-send-email-page',[],$service_result->getErrorMessage());
        }
    }

    public function forget_password_page()
    {
        $request = request();
        $renew_token = $request->input('renew_token');
        return view('shop/member/forget-password-page',['renew_token'=>$renew_token,]);
    }

    public function forget_password()
    {
        $request = request();
        $member_data = ['new_password'=>$request->input('new_password'),'re_new_password'=>$request->input('re_new_password'),'renew_token'=>$request->input('renew_token'),];
        $renew_token = $request->input('renew_token');
        $require_text_params = ['renew_token'=>'renew_token 網址參數','new_password'=>'新密碼','re_new_password'=>'新密碼確認',];
        $check_require_param_result = FormValidateUtility::check_require_param($request,$require_text_params);
        if(!$check_require_param_result->getSuccess()){
            return view('shop/member/forget-password-page',['renew_token'=>$renew_token], $check_require_param_result->getErrorMessage());
        }

        if(!FormValidateUtility::check_password_same($request,['new_password','re_new_password'])->getSuccess()){
            return view('shop/member/forget-password-page',['renew_token'=>$renew_token],['[新密碼] [新密碼確認] 兩個欄位請填寫相同值']);
        }
        
        $check_pattern_text_params = ['new_password'=>['password','新密碼'],];
        $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
        if(!$check_pattern_result->getSuccess()){
            return view('shop/member/forget-password-page',['renew_token'=>$renew_token],$check_pattern_result->getErrorMessage());
        }

        $service_result = $this->memberService->reset_password($member_data);
        if($service_result->getSuccess()){
            MemberAuth::logout();
            SessionUtility::flash("forget_password_success_message",['已成功修改密碼，請重新登入']);
            return redirect("/login");
        }else{
            return view('shop/member/forget-password-page',['renew_token'=>$renew_token],$service_result->getErrorMessage());
        }
    }

    public function member_edit_page()
    {
        $service_result = $this->memberService->get_member_profile();
        if($service_result->getSuccess()){
            $success_message  = [];
            if(SessionUtility::flash("edit_member_profile_success")){
                $success_message = SessionUtility::flash("edit_member_profile_success");
            }

            $member_data = $service_result->getData();
            $name = $member_data['name'];
            $telephone_number = $member_data['telephone_number'];
            return view('shop/member/profile-edit-page',['name'=>$name,'telephone_number'=>$telephone_number],[],$success_message);
        }else{
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            return view('shop/member/profile-edit-page',[],$service_result->getErrorMessage());
        }
    }

    public function member_edit()
    {
        $t_service_result = $this->memberService->get_member_profile();
        if(!$t_service_result->getSuccess()){
            return view('shop/member/profile-edit-page',[],$t_service_result->getErrorMessage());
        }

        $member_data = $t_service_result->getData();
        $name = $member_data['name'];
        $telephone_number = $member_data['telephone_number'];

        $request = request();
        $member_data = ['name'=>$request->input('name')."",'telephone_number'=>$request->input('telephone_number').""];   
        $check_pattern_error_message_arr = [];
        if($member_data['telephone_number']!=''){
            $check_pattern_text_params = ['telephone_number'=>['telephone_number','手機號碼'],];
            $check_pattern_result = FormValidateUtility::check_request_input_pattern($request,$check_pattern_text_params);
            if(!$check_pattern_result->getSuccess()){
                $check_pattern_error_message_arr = $check_pattern_result->getErrorMessage();
            }
        }

        if(mb_strlen($member_data['name'],'utf-8')>=10){
            array_push($check_pattern_error_message_arr,"姓名長度需小於10個字");
        }

        if(count($check_pattern_error_message_arr)!=0){
            return view('shop/member/profile-edit-page',['name'=>$name,'telephone_number'=>$telephone_number],$check_pattern_error_message_arr);
        }
        
        $service_result = $this->memberService->edit_member_profile($member_data);
        if($service_result->getSuccess()){
            SessionUtility::flash("edit_member_profile_success",['已成功更新會員資料']);
            return redirect("/member/edit");
        }else{
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            return view('shop/member/profile-edit-page',['name'=>$name,'telephone_number'=>$telephone_number],$service_result->getErrorMessage());
        }
    }

}

?>