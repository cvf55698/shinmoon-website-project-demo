<?php

use App\Shinmoon\Order\OrderService;
use App\Shinmoon\Cart\CartService;
use App\Shinmoon\Traits\TestServiceTrait;

class OrderServiceTest extends PHPUnit\Framework\TestCase{

    use TestServiceTrait;

    private $orderService;
    private $cartService;
    
    public function setUp():void
    {
        $this->load_test_config();
        $this->orderService = OrderService::getInstance();
        $this->cartService = CartService::getInstance();
        $this->set_test_log_path(TEST_PATH."log/Service/orderServiceTest.log");
    }

    public function testGetOrderDetail()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $product_id = $this->get_test_product_id($this->test_product1);
        $service_result = $this->cartService->add_product_to_cart($product_id);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        }

        $this->assertEquals($service_result->getSuccess(), true);
        $service_result = $this->orderService->get_order_detail();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        } 

        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function test_EditOrderCheckoutSetting_SubmitOrder_GetMemberOrder()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $product_id = $this->get_test_product_id($this->test_product1);
        $service_result = $this->cartService->add_product_to_cart($product_id);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        }

        $this->assertEquals($service_result->getSuccess(), true);
        $service_result = $this->orderService->edit_order_checkout_setting($this->test_order_setting_param_arr_1);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        } 

        $this->assertEquals($service_result->getSuccess(), true);
        $service_result = $this->orderService->submit_order();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        } 

        $commit_order_id = (int) $service_result->getData()['commit_order_id'];
        $service_result = $this->orderService->get_member_order($commit_order_id);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        } 
        
        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testGetMemberOrderList()
    {
        $this->start_session();
        $this->set_login_member_session($this->test_member1);
        $page = 1;
        $service_result = $this->orderService->get_member_order_list($page);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
            $this->close_session();
            $this->assertEquals($service_result->getSuccess(), true);
            return;
        } 

        $this->close_session();
        $this->assertEquals($service_result->getSuccess(), true);
    }
    
}

?>