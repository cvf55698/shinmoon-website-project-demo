<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class OrderCommitTest extends PHPUnit\Framework\TestCase{

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

    public function testOrderCommitPage()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $this->clientUseOrderSettingType1_second_invoice_by_shop($this->test_member1);
        $res = $login_client->request('GET', $this->base_url."orders/submit", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalOrderCommit()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $query_count = 1;
        $this->clientSwitchCartProductCount($this->test_member1,$this->test_product1,$query_count);
        $this->clientUseOrderSettingType1_second_invoice_by_shop($this->test_member1);
        $res = $login_client->request('POST', $this->base_url."orders/submit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($this->test_member1)]),
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 301);
        if($status_code!=301){
            return;
        }

        $redirect_url = $res->getHeader('location')[0];
        $url_arr = [];
        if(!preg_match("/^\/orders\/([1-9][0-9]{0,})$/",$redirect_url,$url_arr)){
            throw new \Exception();
        }

        if(count($url_arr)!=2){
            throw new \Exception();
        }

        $commit_order_id = (int) $url_arr[1];
        $res = $login_client->request('GET', $this->base_url."orders/$commit_order_id", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testOrderListPage()
    {
        $login_client = $this->getLoginClient($this->test_member1);
        $res = $login_client->request('GET', $this->base_url."orders", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>
