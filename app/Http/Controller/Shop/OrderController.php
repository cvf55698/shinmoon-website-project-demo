<?php

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Validate\FormValidateUtility;
use App\Auth\MemberAuth;
use App\Shinmoon\Traits\ControllerTrait;
use App\Shinmoon\Order\OrderUtility;
use App\Page\PageProvider;
use App\Http\Param\ParamFilterUtility;

class OrderController{

    use ControllerTrait;

    public function order_setting_page()
    {
        $service_result = $this->orderService->get_order_detail();
        if( !$service_result->getSuccess()){
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            if(SessionUtility::flash("empty_order_items")){
                return redirect("/cart");
            } 

            return view("shop/order/order-setting-edit-page",[],$service_result->getErrorMessage());
        }

        $query_data = $service_result->getData();
        $order_items = $query_data ['order_items'];
        $order = $query_data ['order'];
        return view("shop/order/order-setting-edit-page",['order_items'=>$order_items,'order'=>$order]);
    }

    public function order_setting()
    {
        $t_service_result = $this->orderService->get_order_detail();
        if( !$t_service_result->getSuccess()){
            return view("shop/order/order-setting-edit-page",[],$t_service_result->getErrorMessage());
        }
        $query_data = $t_service_result->getData();
        $order_items = $query_data ['order_items'];
        $order = $query_data ['order'];
        
        $request = request();
        $param_arr = OrderUtility::get_order_setting_param_arr_from_request($request);
        $utility_result = OrderUtility::order_setting_param_check($param_arr);
        if(!$utility_result->getSuccess()){
            return view("shop/order/order-setting-edit-page",['order_items'=>$order_items,'order'=>$order],$utility_result->getErrorMessage());
        }

        $service_result = $this->orderService-> edit_order_checkout_setting($param_arr);
        if(! $service_result->getSuccess()){
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            if(SessionUtility::flash("empty_order_items")){
                return redirect("/cart");
            }

            return view("shop/order/order-setting-edit-page",['order_items'=>$order_items,'order'=>$order],$service_result->getErrorMessage());
        }

        return redirect("/orders/submit");
    }

    public function order_commit_page()
    {
        $service_result = $this->orderService->get_order_detail();
        if( !$service_result->getSuccess()){
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            if(SessionUtility::flash("empty_order_items")){
                return redirect("/cart");
            }

            return view("shop/order/order-submit-page",[],$service_result->getErrorMessage());
        }

        $check_order_row_form_correct = (bool) $service_result->getData()['check_order_row_form_correct'];
        if(!$check_order_row_form_correct){
            return redirect("/orders/new");
        }

        $query_data = $service_result->getData();
        $order_items = $query_data['order_items'];
        $order = $query_data['order'];
        $invoice_info = $query_data['invoice_info'];
        return view("shop/order/order-submit-page",['order_items'=>$order_items,'order'=>$order,'invoice_info'=>$invoice_info]);
    }

    public function order_commit()
    {
        $service_result = $this->orderService->submit_order();
        if( !$service_result->getSuccess()){
            if(SessionUtility::flash("member_not_exist")){
                return redirect("/login");
            }

            if(SessionUtility::flash("empty_order_items")){
                return redirect("/cart");
            } 

            if(SessionUtility::flash("check_order_row_form_correct_fail")){
                return redirect("/orders/new");
            }

            return view("shop/order/order-submit-fail-page",['submit_fail_error_message_arr'=>$service_result->getErrorMessage(),]);
        }

        $commit_order_id = $service_result->getData()['commit_order_id'];
        SessionUtility::flash("order_submit_success",['訂單送出成功']);
        return redirect("/orders/$commit_order_id");
    }

    public function orders_list_page()
    {
        $request = request();
        $page = PageProvider::get_page($request);
        $service_result = $this->orderService->get_member_order_list($page);
        if(!$service_result->getSuccess()){
            return view("shop/order/order-list-page",[],$service_result->getErrorMessage());
        }

        $service_data = $service_result->getData();
        return view('shop/order/order-list-page',['orders'=>$service_data['orders'],'page_data'=>$service_data['page_data']]);
    }

    public function orders_page($order_id)
    {
        try{
            $order_id = ParamFilterUtility::get_int($order_id);
        }catch(\Exception | \Error $e){
            return view('shop/order/order-page',[],['此筆訂單不存在']);
        }

        $service_result = $this->orderService->get_member_order($order_id);
        if($service_result->getSuccess()){
            $success_message = [];
            if(SessionUtility::flash("order_submit_success")){
                $success_message = SessionUtility::flash("order_submit_success");
            }
            
            return view('shop/order/order-page',$service_result->getData(),[],$success_message);
        }else{
            return view('shop/order/order-page',[],$service_result->getErrorMessage());
        }
    }

}

?>