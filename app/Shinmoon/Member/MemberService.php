<?php

namespace App\Shinmoon\Member;

use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\ServiceTrait;
use App\Http\Url\UrlProvider;
use App\Shinmoon\Member\MemberUtility;
use App\Auth\MemberAuth;
use App\Session\SessionUtility;
use App\Http\Csrf\CsrfUtility;

class MemberService{

    use ServiceTrait;
    
    public function register($member_data)
    {
        $service_result = new ResultData();
        $email = $member_data['email'];
        if( ($email===null) || ($email=='')){
            $service_result->setErrorMessage(['[Email] 欄位不可空白']);
            return $service_result;
        }        

        try{
            $this->begin_transaction();
            if($this->memberRepository->select_member_by_email($email)->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['已有相同 Email 會員註冊']);
                return $service_result;
            }

            $account = HashUtility::html_encode($member_data['account']);
            $password = HashUtility::html_encode($member_data['password']);
            if($this->memberRepository->select_member_by_account($account)->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['已有相同帳號會員註冊']);
                return $service_result;
            }

            $this->memberRepository->insert_member_by_web_register($account,$password,$email);
            $subject = get_site_name().' - 註冊成功通知';
            $login_link = UrlProvider::get_base_url()."login";
            $mail_content = "$email 您好:<br/><br/>您已成功註冊會員，請透過以下連結登入<br/><br/><a href='$login_link'>$login_link</a>";
            $send_result = send_mail($email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法註冊會員帳戶']);
                return $service_result;
            }

            $this->commit();
        }catch(\Error | \Exception $e){
            $this->rollback();
            $service_result->setErrorMessage( ['資料庫連接失敗，無法註冊會員帳戶']);
            return $service_result;
        }

        $service_result->setSuccess(true);
        return $service_result;
    }

    public function web_login($member_data)
    {
        $service_result = new ResultData();
        $account_or_email = HashUtility::html_encode($member_data['account_or_email']);
        $password = HashUtility::html_encode($member_data['password']);
        try{
            $this->begin_transaction();
            $query_result = $this->memberRepository->select_member_by_web_login($account_or_email);
            if(!$query_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['此帳號或 Email 尚未被註冊']);
                return $service_result;
            }

            $member = $query_result->getData()['member'];
            if(MemberUtility::check_select_member_is_oauth($member)){
                $this->rollback();
                $service_result->setErrorMessage( ['請使用社群網站登入此帳號']);
                return $service_result;
            }

            $member_password_hash = $member['password'];
            if(!HashUtility::bcrypt_verify($password,$member_password_hash)){
                $this->rollback();
                $service_result->setErrorMessage( ['帳號密碼錯誤']);
                return $service_result;
            }

            $this->commit();
        }catch(\Error | \Exception $e){
            $this->rollback();
            $service_result->setErrorMessage( ['資料庫連接失敗，無法登入會員帳戶']);
            return $service_result;
        }

        MemberAuth::set_member($member);
        $service_result->setSuccess(true);
        return $service_result;
    }

    public function logout()
    {
        SessionUtility::clear('member');
    }

    public function edit_password($member_data,$is_oauth)
    {
        $service_result = new ResultData();
        if($is_oauth){
            $oauth_config_site_name = MemberAuth::get_member_oauth_site_name();
            $service_result->setErrorMessage( ["利用 $oauth_config_site_name 社群網站登入的帳號，無須修改密碼"]);
            return $service_result;
        }

        $origin_password = HashUtility::html_encode($member_data['origin_password']);
        $new_password = HashUtility::html_encode($member_data['new_password']);
        $re_new_password = HashUtility::html_encode($member_data['re_new_password']);
        $member_id = MemberUtility::get_login_member_id();
        try{
            $this->begin_transaction();
            $this->memberRepository->lock_member_by_id($member_id);
            $query_result = $this->memberRepository->select_member_by_id($member_id);
            if(!$query_result->getSuccess()){
                MemberAuth::logout();
                $this->rollback();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(['此會員帳號不存在，或是已被註銷']);
                return $service_result;
            }

            $member = $query_result->getData()['member'];
            $member_password_hash = $member['password'];
            if(!HashUtility::bcrypt_verify($origin_password,$member_password_hash)){
                $this->rollback();
                $service_result->setErrorMessage( ['帳號原密碼輸入錯誤']);
                return $service_result;
            }

            $new_password_hash = HashUtility::bcrypt_hash($new_password);
            $this->memberRepository->update_member_password($member_id,$new_password_hash);

            $email = $member['email'];
            $subject = get_site_name().' - 密碼更新成功通知';
            $login_link = UrlProvider::get_base_url()."login";
            $mail_content = "$email 您好:<br/><br/>您已成功更新密碼，請透過以下連結重新登入<br/><br/><a href='$login_link'>$login_link</a>";
            $send_result = send_mail($email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法註冊會員帳戶']);
                return $service_result;
            }

            $this->commit();
        }catch(\Error | \Exception $e){
            $this->rollback();
            $service_result->setErrorMessage( ['資料庫連接失敗，無法修改會員密碼']);
            return $service_result;
        }

        $service_result->setSuccess(true);
        return $service_result;
    }
    
    public function edit_email($member_data)
    {
        $service_result = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $this->memberRepository->lock_member_by_id($member_id);
            $query_result = $this->memberRepository->select_member_by_id($member_id);
            if(!$query_result->getSuccess()){
                MemberAuth::logout();
                $this->rollback();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(['此會員帳號不存在，或是已被註銷']);
                return $service_result;
            }

            $member = $query_result->getData()['member'];
            $new_email = $member_data['email'];
            $member_origin_email = $member['email'];
            if($new_email==$member_origin_email){
                $this->rollback();
                $service_result->setErrorMessage(['請填寫跟當前帳號使用的 Email 不同的 Email']);
                return $service_result;
            }

            if($this->memberRepository->select_member_by_email($new_email)->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['已有相同 Email 會員註冊']);
                return $service_result;
            }

            $this->memberRepository->update_member_email($member_id,$new_email);

            $subject = get_site_name().' - Email 更新成功通知';
            $mail_content = "$new_email 您好:<br/><br/>您已經成功將會員帳號 Email 信箱，更新為 $new_email";
            $send_result = send_mail($new_email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法修改會員 Email']);
                return $service_result;
            }

			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法修改會員 Email']);
            return $service_result;
	    }

		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function send_reset_password_email($member_data)
    {
		$service_result = new ResultData();
        $email = HashUtility::html_encode($member_data['email']);
		try{
			$this->begin_transaction();
            $this->memberRepository->lock_member_by_email($email);
            $query_result = $this->memberRepository->select_member_by_email($email);
            if(!$query_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['此 Email 尚未被註冊']);
                return $service_result;
            }
            
            $member = $query_result->getData()['member'];
            if(MemberUtility::check_select_member_is_oauth($member)){
                $this->rollback();
                $oauth_config_site_name = MemberUtility::get_select_member_oauth_site_name($member);
                $service_result->setErrorMessage( ["利用 $oauth_config_site_name 社群網站登入的帳號，無須修改密碼"]);
                return $service_result;
            }
            
            $member_id = MemberUtility::get_select_member_id($member);
            $reset_password_token = CsrfUtility::generate_token('member_send_new_password_email');
            $this->memberRepository->update_member_reset_passowrd($member_id,$reset_password_token);

            $subject = get_site_name().' - 密碼重設步驟';
            $new_password_link = UrlProvider::get_base_url()."password?renew_token=$reset_password_token";
            $mail_content = "$email 您好:<br/><br/>您可以透過以下連結重新設定您的密碼：<br/><br/><a href='$new_password_link'>$new_password_link</a>"
                        ."<br/><br/>如果您對這個操作沒有印象，請忽略這封 E-mail";
            $send_result = send_mail($email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法寄送重設密碼確認信']);
                return $service_result;
            }
            
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法寄送重設密碼確認信']);
            return $service_result;
		}

        $service_result->setData(['reset_password_token'=>$reset_password_token]);
		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function reset_password($member_data)
    {
		$service_result = new ResultData();    
        $renew_token = HashUtility::html_encode($member_data['renew_token']);
        $new_password = HashUtility::html_encode($member_data['new_password']);
        $re_new_password = HashUtility::html_encode($member_data['re_new_password']);
		try{
			$this->begin_transaction();
            $this->memberRepository->lock_member_by_reset_password($renew_token);
            $query_result = $this->memberRepository->select_member_by_reset_password($renew_token);
            if(!$query_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['請重新申請寄送重設密碼驗證信']);
                return $service_result;
            }

            $member = $query_result->getData()['member'];
            if(MemberUtility::check_select_member_is_oauth($member)){
                $this->rollback();
                $oauth_config_site_name = MemberUtility::get_select_member_oauth_site_name($member);
                $service_result->setErrorMessage( ["利用 $oauth_config_site_name 社群網站登入的帳號，無須修改密碼"]);
                return $service_result;
            }

            $member_id = MemberUtility::get_select_member_id($member);
            $new_password_hash = HashUtility::bcrypt_hash($new_password);
            $this->memberRepository->update_member_password_by_reset_password_email($member_id,$new_password_hash);

            $email = MemberUtility::get_select_member_email($member);
            $subject = get_site_name().' - 密碼更新成功通知';
            $login_link = UrlProvider::get_base_url()."login";
            $mail_content = "$email 您好:<br/><br/>您已成功更新密碼，請透過以下連結重新登入<br/><br/><a href='$login_link'>$login_link</a>";
            $send_result = send_mail($email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法修改會員密碼']);
                return $service_result;
            }

			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法修改會員密碼']);
            return $service_result;
		}

		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function get_member_profile()
    {
		$service_result = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $query_result = $this->memberRepository->select_member_by_id($member_id);
            if(!$query_result->getSuccess()){
                MemberAuth::logout();
                $this->rollback();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(['此會員帳號不存在，或是已被註銷']);
                return $service_result;
            }

            $member = $query_result->getData()['member'];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法取得會員資料']);
            return $service_result;
		}

        MemberAuth::set_member($member);
        $data = ['name'=>HashUtility::html_decode($member['name']),'telephone_number'=>HashUtility::html_decode($member['telephone_number'])];
        $service_result->setData($data);
		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function edit_member_profile($member_data)
    {
		$service_result = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $this->memberRepository->lock_member_by_id($member_id);
            $query_result = $this->memberRepository->select_member_by_id($member_id);
            if(!$query_result->getSuccess()){
                MemberAuth::logout();
                $this->rollback();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(['此會員帳號不存在，或是已被註銷']);
                return $service_result;
            }

            $this->memberRepository->update_member_profile_by_id($member_id,$member_data);
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法修改會員資料']);
            return $service_result;
		}

		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function oauth_login($member_data)
    {
		$service_result = new ResultData();
        $oauth_type = (int) $member_data['oauth_type_id'];
        $oauth_id = $member_data['oauth_id'];
        $email = $member_data['email'];
		try{
			$this->begin_transaction();
            $this->memberRepository->lock_member_by_oauth($oauth_type,$oauth_id);
            $query_result = $this->memberRepository->select_member_by_oauth_login($oauth_type,$oauth_id);
            if(!$query_result->getSuccess()){
                if( ($email===null) || ($email=='')){
                    $this->rollback();
                    $service_result->setErrorMessage(['Email 不可空白']);
                    return $service_result;
                }   

                if($this->memberRepository->select_member_by_email($email)->getSuccess()){
                    $this->rollback();
                    $service_result->setErrorMessage(['已有相同 Email 會員註冊']);
                    return $service_result;
                }
                
                $this->memberRepository->insert_member_by_oauth_login($oauth_type,$oauth_id,$email);
                $query_result = $this->memberRepository->select_member_by_oauth_login($oauth_type,$oauth_id);
                if(!$query_result->getSuccess()){
                    $this->rollback();
			        $service_result->setErrorMessage( ['資料庫連接失敗，無法進行社群帳號登入']);
                    return $service_result;
                }

                $subject = get_site_name().' - 註冊成功通知';
                $login_link = UrlProvider::get_base_url()."login";
                $mail_content = "$email 您好:<br/><br/>您已成功註冊會員，請透過以下連結登入<br/><br/><a href='$login_link'>$login_link</a>";
                $send_result = send_mail($email,$subject,$mail_content);
                if(!$send_result->getSuccess()){
                    $this->rollback();
                    $service_result->setErrorMessage(['資料庫連接失敗，無法進行社群帳號登入']);
                    return $service_result;
                }

            }

            $member = $query_result->getData()['member'];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法進行社群帳號登入']);
            return $service_result;
		}

        MemberAuth::set_member($member);
		$service_result->setSuccess(true);
       	return $service_result;
    }

}

?>