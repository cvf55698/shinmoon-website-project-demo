<?php

use App\Shinmoon\Cart\CartService;
use App\Shinmoon\Traits\TestServiceTrait;

class CartServiceTest extends PHPUnit\Framework\TestCase{

    use TestServiceTrait;

    private $cartService;
    
    public function setUp():void
    {
        $this->load_test_config();
        $this->cartService = CartService::getInstance();
        $this->set_test_log_path(TEST_PATH."log/Service/cartServiceTest.log");
    }

    public function tearDown() :void
    {
        $this->cartService = null;
    }

    public function testAddProductToCart()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $product_id = $this->get_test_product_id($this->test_product1);
        $service_result = $this->cartService->add_product_to_cart($product_id);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testListCartOrderItems()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $service_result = $this->cartService->list_cart_order_items();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testSwitchCartOrderProductQuantity()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $product_id = $this->get_test_product_id($this->test_product1);
        $query_count = 1;
        $service_result = $this->cartService->switch_cart_order_product_quantity($product_id,$query_count);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testRemoveCartOrderProduct()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $product_id = $this->get_test_product_id($this->test_product1);
        $service_result = $this->cartService->remove_cart_order_product($product_id);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

}

?>