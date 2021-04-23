<?php

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class SwitchCartProductQuantityTest extends PHPUnit\Framework\TestCase{

    use TestControllerTrait;

    public function setUp():void
    {
        $this->load_test_config();
        $this->set_test_log_path(TEST_PATH."log/Controller/cartControllerTest.log");
        $this->base_url = UrlProvider::get_base_url();
    }

    public function tearDown() :void
    {

    }

    public function testNormalSwitchCartProductQuantity()
    {
        $product_id = $this->get_test_product_id($this->test_product1);
        $login_client = $this->getLoginClient($this->test_member1);
        $res = $login_client->request('POST', $this->base_url."cart/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id,'member_id'=>$this->get_test_member_id($this->test_member1)]),
                'product_id'=> "".$product_id,
                'query_count'=>5,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
        $json = json_decode((string)$res->getBody(),true);
        $this->assertEquals($json['success'] , true);
    }

    public function testQuantityShouldBeIntegerLargerThan0()
    {
        $product_id = $this->get_test_product_id($this->test_product1);
        $login_client = $this->getLoginClient($this->test_member1);
        $res = $login_client->request('POST', $this->base_url."cart/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id,'member_id'=>$this->get_test_member_id($this->test_member1)]),
                'product_id'=> "".$product_id,
                'query_count'=>-2,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
        $json = json_decode((string)$res->getBody(),true);
        $this->assertEquals($json['success'] , false);
    }

    public function testProductShouldExist()
    {
        $product_id = 1000;
        $login_client = $this->getLoginClient($this->test_member1);
        $res = $login_client->request('POST', $this->base_url."cart/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id,'member_id'=>$this->get_test_member_id($this->test_member1)]),
                'product_id'=> "".$product_id,
                'query_count'=>5,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
        $json = json_decode((string)$res->getBody(),true);
        $this->assertEquals($json['success'] , false);
    }

}

?>