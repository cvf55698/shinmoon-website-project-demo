<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class OrderSettingTest extends PHPUnit\Framework\TestCase{

    use TestControllerTrait;

    public function setUp():void
    {
        $this->load_test_config();
        $this->set_test_log_path(TEST_PATH."log/Controller/orderControllerTest.log");
        $this->base_url = UrlProvider::get_base_url();
    }

    public function tearDown() :void
    {

    }

    public function testOrderSettingPage()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('GET', $this->base_url."orders/new", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalOrderSetting_type1_second_invoice_by_shop()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/orders/submit");
    }

    public function testNormalOrderSetting_type2_second_invoice_by_member_carrier()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'2',
                'member_carrier'=>$this->generate_random_member_carrier(),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/orders/submit");
    }

    public function testNormalOrderSetting_type3_choose_donate()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'donate',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/orders/submit");
    }

    public function testNormalOrderSetting_type3_other_donate()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'donate',
                'donate_invoice_type'=>'2',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>$this->generate_random_other_donate(),
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        $this->assertEquals($res->getHeader('location')[0] , "/orders/submit");
    }

    public function testRequireNameTelephoneNumber()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> '',
                'telephone_number'=>'',
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNameLengthCannotLongerThan10()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(12),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testTelephoneNumberShouldConformToTheFormat()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_string(12),
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }
    
    public function testOtherDonateShouldConformToTheFormat()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'donate',
                'donate_invoice_type'=>'2',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>$this->generate_random_string(20),
                'second_invoice_type'=>'1',
                'member_carrier'=>'',
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testMemberCarrierShouldConformToTheFormat()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
                'name'=> $this->generate_random_string(8),
                'telephone_number'=>$this->generate_random_telephone_number(),
                'invoice_type'=>'second_invoice',
                'donate_invoice_type'=>'1',
                'choose_donate'=>'25885:財團法人伊甸社會福利基金會',
                'other_donate'=>'',
                'second_invoice_type'=>'2',
                'member_carrier'=>$this->generate_random_string(30),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>