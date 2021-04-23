<?php

namespace App\Shinmoon\Order;

use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\ServiceTrait;
use App\Http\Url\UrlProvider;
use App\Auth\MemberAuth;
use App\Session\SessionUtility;
use App\Http\Csrf\CsrfUtility;
use App\Shinmoon\Order\OrderUtility;
use App\Shinmoon\Member\MemberUtility;

class OrderService{

    use ServiceTrait;

    private function common_get_order_detail($member_id)
    {
        $service_result  = new ResultData();
        $query_result = $this->cartRepository->get_member_cart_order_items($member_id);
        $query_data = $query_result->getData();
        if(!$query_result->getSuccess()){
            $this->rollback();
            if($query_data['member_not_exist']){
                MemberAuth::logout();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(["此會員帳號不存在，或是已被註銷"]);
                return $service_result;
            }else{
                SessionUtility::flash("empty_order_items",["用戶尚未將任何商品加入購物車"]);
                $service_result->setErrorMessage(["用戶尚未將任何商品加入購物車"]);
                return $service_result;
            }

        }

        $order_items = $query_data['member_cart_order_items'];
        $order_id = $query_data['member_cart_order_id'];
        $this->orderRepository->lock_order($order_id);
        $this->orderItemRepository->lock_order_items($order_items);
        $this->orderRepository->update_order_total($order_id);
        $query_result = $this->orderRepository->select_order($order_id);
        if(!$query_result->getSuccess()){
            throw new \Exception();
        }

        $member_cart_order = $query_result->getData()['order'];
        $invoice_info = OrderUtility::get_invoice_info($member_cart_order);
        $check_order_row_form_correct = OrderUtility::check_order_row_form_correct($member_cart_order);
        $query_data = [
            'order_items'=>$order_items,
            'order'=> $member_cart_order,
            'order_id'=>(int) $member_cart_order['id'],
            'invoice_info'=>$invoice_info,
            'check_order_row_form_correct'=>$check_order_row_form_correct
        ];

        $service_result->setData($query_data);
		$service_result ->setSuccess(true);
        return $service_result;
    }

    public function get_order_detail()
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
        try{
			$this->begin_transaction();
            $temp_service_result = $this->common_get_order_detail($member_id);
            if(! $temp_service_result->getSuccess()){
                return $temp_service_result;
            }

			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法取得訂單明細']);
            return $service_result ;
		}

        $service_result->setData($temp_service_result->getData());
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function edit_order_checkout_setting($param_arr)
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
			$temp_service_result = $this->common_get_order_detail($member_id);
            if(! $temp_service_result->getSuccess()){
                return $temp_service_result;
            }	 

            $order_id = (int) $temp_service_result->getData()['order_id'];
            $this->orderRepository->update_order_checkout_setting($order_id,$param_arr);
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法設定結帳資訊']);
            return $service_result ;
		}

		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function submit_order()
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $temp_service_result = $this->common_get_order_detail($member_id);
            if(! $temp_service_result->getSuccess()){
                return $temp_service_result;
            }	       

            $check_order_row_form_correct = (bool) $temp_service_result->getData()['check_order_row_form_correct'];
            if(!$check_order_row_form_correct){
                $this->rollback();
                SessionUtility::flash("check_order_row_form_correct_fail",true);
                return $service_result;
            }

            $order_id = (int) $temp_service_result->getData()['order_id'];
            $order_items = $temp_service_result->getData()['order_items'];
            $this->productRepository->lock_products_by_order_items($order_items);
            $query_result = $this->orderItemRepository->commit_order_items($order_id);
            if(!$query_result->getSuccess()){
                $this->rollback();
                $this->begin_transaction();
                foreach($query_result->getData()['drop_order_items_products'] as $product_id){
                    $this->orderItemRepository->remove_cart_order_item($product_id,$order_id,$member_id);
                }

                $this->commit();
                $service_result ->setErrorMessage($query_result->getData()['error_arr']);
                return $service_result ;
            }

            $this->memberRepository->clear_member_cart_order_id($member_id);
            $this->orderRepository->commit_order($order_id);

            $email = MemberUtility::get_login_member_email();
            $subject = get_site_name().' - 感謝您的訂購';
            $order_link = UrlProvider::get_base_url()."orders/$order_id";
            $mail_content = "$email 您好:<br/><br/>您已成功送出訂單，請透過以下連結查看訂單資訊<br/><br/><a href='$order_link'>$order_link</a>";
            $send_result = send_mail($email,$subject,$mail_content);
            if(!$send_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(['資料庫連接失敗，無法送出訂單']);
                return $service_result;
            }

			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法送出訂單']);
            return $service_result ;
		}

        $service_result->setData(['commit_order_id'=>(int) $order_id,]);
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function get_member_order_list($page)
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $orders = [];
            $query_result = $this->orderRepository->list_member_history_orders($member_id,$page);
            if($query_result->getSuccess()){
                $orders = $query_result->getData()['orders'];
            }

            $page_data = $query_result->getData()['page_data'];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法取得用戶歷史訂單列表']);
            return $service_result ;
		}

        $service_result ->setData(['orders'=>$orders,'page_data'=>$page_data,]);
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function get_member_order($order_id)
    {
        $service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $query_result = $this->orderRepository->get_member_history_order($order_id,$member_id);
            if(!$query_result->getSuccess()){
                $this->rollback();
                $service_result->setErrorMessage(["此筆訂單不存在"]);
                return $service_result;
            }

            $query_data = $query_result->getData();
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法取得會員訂單明細']);
            return $service_result ;
		}

        $service_result ->setData($query_data);
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

}

?>