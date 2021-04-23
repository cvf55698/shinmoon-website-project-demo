<?php

namespace App\Shinmoon\Traits;

use App\Shinmoon\Traits\TestTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use App\Http\Url\UrlProvider;
use App\Http\Csrf\CsrfUtility;

trait TestControllerTrait{

    use TestTrait;

    public function getGuzzleHttpClient()
    {
        return new Client(['cookies' => CookieJar::fromArray([], $this->base_url),'verify' => false]);
    }

    public function getLoginClient($test_member,$password = null)
    {
        if($password == null){
            $password = $this->get_test_member_password($test_member);
        }

        $client = $this->getGuzzleHttpClient();
        $res = $client->request('POST', $this->base_url."login", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_login'),
                'account_or_email'=>$this->get_test_member_account($test_member),
                'password'=>$password,
            ],
        ]);
        $status_code = $res->getStatusCode();
        if($status_code!=301){
            throw new \Exception();
        }

        return $client;
    }

    public function clientAddProductToCart($test_member,$test_product)
    {
        $product_id = $this->get_test_product_id($test_product);
        $login_client = $this->getLoginClient($test_member);
        $res = $login_client->request('POST', $this->base_url."cart/add", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id,'member_id'=>$this->get_test_member_id($test_member)]),
                'product_id'=> "".$product_id,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $json = json_decode((string)$res->getBody(),true);
        if( ($status_code!=200) || (!$json['success']) ){
            throw new \Exception();
        }
    }

    public function clientSwitchCartProductCount($test_member,$test_product,$query_count)
    {
        $product_id = $this->get_test_product_id($test_product);
        $login_client = $this->getLoginClient($test_member);
        $res = $login_client->request('POST', $this->base_url."cart/edit", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id,'member_id'=>$this->get_test_member_id($test_member)]),
                'product_id'=> "".$product_id,
                'query_count'=>"".$query_count,
            ],
        ]);
        $status_code = $res->getStatusCode();
        $json = json_decode((string)$res->getBody(),true);
        if( ($status_code!=200) || (!$json['success']) ){
            throw new \Exception();
        }
    }

    public function clientUseOrderSettingType1_second_invoice_by_shop($test_member)
    {
        $login_client = $this->getLoginClient($test_member);
        $res = $login_client->request('POST', $this->base_url."orders/new", [
            'allow_redirects' => false,
            'form_params' => [
                'csrf_token'=>CsrfUtility::generate_token('member',['member_id'=>$this->get_test_member_id($test_member)]),
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
        if( ($status_code!=301) || ($res->getHeader('location')[0]!='/orders/submit') ){
            throw new \Exception();
        }
    }

}

?>