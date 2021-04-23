<?php

use App\Shinmoon\Member\MemberService;
use App\Shinmoon\Traits\TestServiceTrait;

class MemberServiceTest extends PHPUnit\Framework\TestCase{

    use TestServiceTrait;

    private $memberService;
    
    public function setUp():void
    {
        $this->load_test_config();
        $this->memberService = MemberService::getInstance();
        $this->set_test_log_path(TEST_PATH."log/Service/memberServiceTest.log");
    }

    public function tearDown() :void
    {
        $this->memberService = null;
    }

    function testRegister()
    {
        $service_result = $this->memberService->register(['email'=>$this->generate_random_email(),'account'=>$this->generate_random_string(10),'password'=>$this->common_password]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }
    
    function testLogin()
    {
        $service_result = $this->memberService->web_login(['account_or_email'=>$this->get_test_member_account($this->test_member1)
            ,'password'=>$this->get_test_member_password($this->test_member1)]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }
    
    function testEditPassword()
    {
        $this->start_session();
        $password = $this->get_test_member_password($this->test_member2);
        $this->set_login_member_session($this->test_member2);
        $service_result = $this->memberService->edit_password(['origin_password'=>$password,'new_password'=>$password,'re_new_password'=>$password],false);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }
    
    function testEditEmail()
    {
        $this->start_session();
        $random_string = $this->generate_random_string(7);
        $this->set_login_member_session($this->test_member2);
        $service_result = $this->memberService->edit_email(['email'=>$this->generate_random_email()]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $service_result = $this->memberService->edit_email(['email'=>$this->get_test_member_email($this->test_member2)]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }
    
    function testForgetPassword()
    {
        $email = $this->get_test_member_email($this->test_member1);
        $service_result = $this->memberService->send_reset_password_email(['email'=>$email]);
        $this->assertEquals($service_result->getSuccess(), true);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            return;
        }

        $password = $this->get_test_member_password($this->test_member1);
        $reset_password_token = $service_result->getData()['reset_password_token'];
        $service_result = $this->memberService->reset_password(['renew_token'=>$reset_password_token,'new_password'=>$password,'re_new_password'=>$password]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    function testGetMemberProfile()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member2);
        $service_result = $this->memberService->get_member_profile();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }
    
    function testEditMemberProfile()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member2);
        $service_result = $this->memberService->edit_member_profile(['name'=>$this->generate_random_string(10),'telephone_number'=>$this->generate_random_telephone_number()]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    function testOauthLogin()
    {
        $service_result = $this->memberService->oauth_login(['oauth_type_id'=>1,'oauth_id'=>$this->generate_random_string(10),'email'=>$this->generate_random_email()]);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

}

?>