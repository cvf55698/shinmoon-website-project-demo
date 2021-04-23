<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class UpdatePasswordTest extends PHPUnit\Framework\TestCase{

    use TestControllerTrait;

    public function setUp():void
    {
        $this->load_test_config();
        $this->set_test_log_path(TEST_PATH."log/Controller/memberControllerTest.log");
        $this->base_url = UrlProvider::get_base_url();
    }

    public function tearDown() :void
    {

    }

    public function testUpdatePasswordPage()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('GET', $this->base_url."password/edit", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalUpdatePassword()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $origin_password = $this->get_test_member_password($this->test_member2);
        $new_password = $this->generate_random_string(10);

        $res = $login_client->request('POST', $this->base_url."password/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'origin_password'=>$origin_password,
                'new_password'=>$new_password,
                're_new_password'=>$new_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/login");
        
        
        $login_client = $this->getLoginClient($this->test_member2,$new_password);
        $res = $login_client->request('POST', $this->base_url."password/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'origin_password'=>$new_password,
                'new_password'=>$origin_password,
                're_new_password'=>$origin_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/login");
    }

    public function testOriginPassword_NewPassword_CannotBeSame()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $origin_password = $this->get_test_member_password($this->test_member2);
        $res = $login_client->request('POST', $this->base_url."password/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'origin_password'=>$origin_password,
                'new_password'=>$origin_password,
                're_new_password'=>$origin_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testOriginNewPassword_ReNewPassword_ShouldBeSame()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $origin_password = $this->get_test_member_password($this->test_member2);
        $new_password = $this->generate_random_string(10);

        $res = $login_client->request('POST', $this->base_url."password/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'origin_password'=>$origin_password,
                'new_password'=>$new_password,
                're_new_password'=>$new_password."AA",
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>