<?php

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Validate\FormValidateUtility;
use App\Auth\MemberAuth;
use App\Shinmoon\Traits\ControllerTrait;
use App\Page\PageProvider;
use App\Http\Param\ParamFilterUtility;
use App\Http\Csrf\CsrfUtility;
use App\Hash\HashUtility;

class CartController{

    use ControllerTrait;

    private function common_filter_check($need_login_message)
    {
        if(!MemberAuth::check()){
            return json(['success'=>false,'error_message'=>"請重新登入後，$need_login_message",'redirect_login'=>true]);
        }

        $request = request();
        $product_id = $request->input("product_id");
        try{
            $product_id = ParamFilterUtility::get_int($product_id);
            
        }catch(\Exception | \Error $e){
            return json(['success'=>false,'error_message'=>'商品種類不存在 '.$product_id]);
        }

        $csrf_token = $request->input("csrf_token");
        if($csrf_token==null){
            return json(['success'=>false,'error_message'=>'缺乏驗證參數']);
        }

        if(!CsrfUtility::verify_hash('member_operate_cart_product',$csrf_token, ['product_id'=>$product_id])){
            return json(['success'=>false,'error_message'=>'驗證參數錯誤']);
        }

        SessionUtility::flash("product_id",(int)$product_id);
        return json(['success'=>true]);
    }

    private function common_ajax_response($service_result)
    {
        if($service_result->getSuccess()){
            return json(['success'=>true]);
        }else{
            if(SessionUtility::flash("member_not_exist")){
                MemberAuth::logout();
                return json(['success'=>false,'error_message'=>'此會員帳號不存在，或是已被註銷','redirect_login'=>true]);
            }

            return json(['success'=>false,'error_message'=>$service_result->getErrorMessage()[0]]);
        }
    }

    public function add_product_to_cart()
    {
        $response = $this->common_filter_check("再將商品加入購物車");
        $json_res = json_decode($response->response_content,true);
        if(!$json_res['success']){
            return $response;
        }

        $product_id = (int) SessionUtility::flash("product_id");
        $service_result = $this->cartService->add_product_to_cart($product_id);
        return $this->common_ajax_response($service_result);
    }

    public function cart_edit_page()
    {
        $service_result = $this->cartService->list_cart_order_items();
        if($service_result->getSuccess()){
            $service_result_data = $service_result->getData();
            if($service_result_data['empty_order_items']){
                return view("shop/cart/cart-edit-page",['order_items'=>[]]);
            }else{
                $order_items = $service_result_data['order_items'];
                $total = $service_result_data['total'];
                return view("shop/cart/cart-edit-page",['order_items'=>$order_items,'total'=>$total]);
            }
        }else{
            if(SessionUtility::flash("member_not_exist")){
                MemberAuth::logout();
                return redirect("/login");
            }

            return view("shop/cart/cart-edit-page",[],$service_result->getErrorMessage());
        }
    }

    public function switch_cart_product_quantity()
    {
        $response = $this->common_filter_check("再調整購物車商品數量");
        $json_res = json_decode($response->response_content,true);
        if(!$json_res['success']){
            return $response;
        }

        $product_id = (int) SessionUtility::flash("product_id");
        $request = request();
        $query_count = $request->input("query_count");
        try{
            $query_count = ParamFilterUtility::get_int($query_count);
        }catch(\Exception | \Error $e){
            return json(['success'=>false,'error_message'=>'商品數量請輸入合法數字']);
        }

        $service_result = $this->cartService->switch_cart_order_product_quantity($product_id,$query_count);
        return $this->common_ajax_response($service_result);
    }

    public function delete_cart_product()
    {
        $response = $this->common_filter_check("再將商品移出購物車");
        $json_res = json_decode($response->response_content,true);
        if(!$json_res['success']){
            return $response;
        }

        $product_id = (int) SessionUtility::flash("product_id");
        $service_result = $this->cartService->remove_cart_order_product($product_id);
        return $this->common_ajax_response($service_result);
    }

}

?>