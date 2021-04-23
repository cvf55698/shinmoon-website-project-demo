<?php

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class WebLoginTest extends PHPUnit\Framework\TestCase{

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

    public function testLoginPage()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('GET', $this->base_url."login", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalLogin()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."login", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_login'),
                'account_or_email'=>$this->get_test_member_account($this->test_member1),
                'password'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/");
    }

    public function testAccountOrEmailShouldExist()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."login", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_login'),
                'account_or_email'=>$this->generate_random_string(10),
                'password'=>$this->common_password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testPasswordShouldBeCorrect()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."login", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_login'),
                'account_or_email'=>$this->get_test_member_account($this->test_member1),
                'password'=>$this->generate_random_string(10),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>