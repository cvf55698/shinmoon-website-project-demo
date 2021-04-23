<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class UpdateProfileTest extends PHPUnit\Framework\TestCase{

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

    public function testUpdateProfilePage()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('GET', $this->base_url."member/edit", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalUpdateProfile()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('POST', $this->base_url."member/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'name'=>$this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/member/edit");
    }

    public function testNameLengthCannotLongerThan10()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('POST', $this->base_url."member/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'name'=>$this->generate_random_string(12),
                'telephone_number'=>$this->generate_random_telephone_number(),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testTelephoneNumberShouldConformToTheFormat()
    {
        $login_client = $this->getLoginClient($this->test_member2);
        $res = $login_client->request('POST', $this->base_url."member/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member2),]),
                'name'=>$this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_string(10),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>