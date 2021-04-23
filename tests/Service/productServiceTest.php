<?php

use App\Shinmoon\Product\ProductService;
use App\Shinmoon\Traits\TestServiceTrait;

class ProductServiceTest extends PHPUnit\Framework\TestCase{

    use TestServiceTrait;

    private $productService;
    
    public function setUp():void
    {
        $this->load_test_config();
        $this->productService = ProductService::getInstance();
        $this->set_test_log_path(TEST_PATH."log/Service/productServiceTest.log");
    }

    public function tearDown() :void
    {
        $this->productService = null;
    }

    public function testListProductCategory()
    {
        $service_result = $this->productService->list_product_category();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testGetProductCategory()
    {
        $service_result = $this->productService->get_product_category(1);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testListProductOfCategory()
    {
        $service_result = $this->productService->list_product_of_category(1,1);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testGetProduct()
    {
        $service_result = $this->productService->get_product(1);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testSearchProduct()
    {
        $service_result = $this->productService->search_product("C",1);
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

    public function testGetRankProducts()
    {
        $service_result = $this->productService->get_rank_products();
        if(!$service_result->getSuccess()){
            $this->write_to_log($service_result);
        }

        $this->assertEquals($service_result->getSuccess(), true);
    }

}

?>