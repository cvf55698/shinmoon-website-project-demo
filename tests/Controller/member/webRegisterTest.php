<?php

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class WebRegisterTest extends PHPUnit\Framework\TestCase{

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

    public function testRegisterPage()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('GET', $this->base_url."register", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalRegister()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>$this->generate_random_string(10),
                'email'=>$this->generate_random_email(),
                'password'=>$this->common_password,
                'repassword'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/login");
    }

    public function testEmailCannotBeRegistered()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>$this->generate_random_string(10),
                'email'=>$this->get_test_member_email($this->test_member1),
                'password'=>$this->common_password,
                'repassword'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/register");
    }

    public function testAccountCannotBeRegistered()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>$this->get_test_member_account($this->test_member1),
                'email'=>$this->generate_random_email(),
                'password'=>$this->common_password,
                'repassword'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/register");
    }
    
    public function testNamePasswordEmailRequired()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>'',
                'email'=>'',
                'password'=>'',
                'repassword'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNameCannotLongerThan30()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>$this->generate_random_string(40),
                'email'=>$this->generate_random_email(),
                'password'=>$this->common_password,
                'repassword'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testPasswordRepasswordShouldBeSame()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."register", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_register'),
                'account'=>$this->generate_random_string(10),
                'email'=>$this->generate_random_email(),
                'password'=>$this->common_password,
                'repassword'=>$this->common_password."AA",
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }
    
}

?>