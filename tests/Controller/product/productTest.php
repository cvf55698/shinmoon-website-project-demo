<?php 

use App\Shinmoon\Traits\TestControllerTrait;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;
use GuzzleHttp\Cookie\CookieJar;

class ProductTest extends PHPUnit\Framework\TestCase{

    use TestControllerTrait;

    public function setUp():void
    {
        $this->load_test_config();
        $this->set_test_log_path(TEST_PATH."log/Controller/productControllerTest.log");
        $this->base_url = UrlProvider::get_base_url();
    }

    public function tearDown() :void
    {

    }

    public function testNormalCategoryProductsPage()
    {
        $product_id = $this->get_test_product_id($this->test_product1);
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('GET', $this->base_url."product/category/$product_id?page=1", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalProductPage()
    {
        $product_id = $this->get_test_product_id($this->test_product1);
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('GET', $this->base_url."product/$product_id", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

    public function testNormalProductSearch()
    {
        $client = $this->getGuzzleHttpClient();
        $res = $client->request('GET', $this->base_url."search?keyword=C D", [
            'allow_redirects' => false,
        ]);
        $status_code = $res->getStatusCode();
        $this->assertEquals($status_code , 200);
    }

}

?>