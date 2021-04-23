<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class UpdateEmailTest extends PHPUnit\Framework\TestCase{

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

    public function testUpdateEmailPage()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('GET', $this->base_url."email/edit", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalUpdateEmail()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $new_email = $this->generate_random_email();

        $res = $login_client->request('POST', $this->base_url."email/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'email'=>$new_email,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/email/edit");

        $res = $login_client->request('POST', $this->base_url."email/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'email'=>$this->get_test_member_email($this->test_member2),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/email/edit");
    }

    public function testNewEmailCannotBeRegistered()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $new_email = $this->get_test_member_email($this->test_member1);

        $res = $login_client->request('POST', $this->base_url."email/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'email'=>$new_email,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNewEmailCannotBeSameWithOriginEmail()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $new_email = $this->get_test_member_email($this->test_member2);

        $res = $login_client->request('POST', $this->base_url."email/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'email'=>$new_email,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }
    
}

?>