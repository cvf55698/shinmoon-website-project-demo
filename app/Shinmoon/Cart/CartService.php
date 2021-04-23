<?php

namespace App\Shinmoon\Cart;

use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\ServiceTrait;
use App\Http\Url\UrlProvider;
use App\Auth\MemberAuth;
use App\Session\SessionUtility;
use App\Http\Csrf\CsrfUtility;
use App\Shinmoon\Member\MemberUtility;

class CartService{

    use ServiceTrait;

    private function common_handle_member_cart_product($member_id,$product_id)
    {
        $service_result  = new ResultData();
        $query_result = $this->cartRepository->get_member_cart_order($member_id);
        if(!$query_result->getSuccess()){
            $query_data = $query_result->getData();
            if($query_data['member_not_exist']){
                $this->rollback();
                SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                $service_result->setErrorMessage(["此會員帳號不存在，或是已被註銷"]);
                return $service_result;
            }

            $member_cart_order_id = (int) $this->orderRepository->insert_order($member_id)->getData()['last_insert_order_id'];
            $this->memberRepository->update_member_cart_order_id($member_id,$member_cart_order_id);
        }else{
            $member_cart_order_id = (int) $query_result->getData()['member_cart_order']['id'];
        }

        $this->orderItemRepository->lock_order_item($product_id,$member_cart_order_id);
        $query_result = $this->productRepository->get_product_available($product_id);
        if(!$query_result->getSuccess()){
            $this->orderItemRepository->remove_cart_order_item($product_id,$member_cart_order_id,$member_id);
            $this->commit();
            $service_result->setErrorMessage($query_result->getErrorMessage());
            return $service_result ;
        }

        $service_result->setData(['member_cart_order_id'=>$member_cart_order_id]);
        $service_result->setSuccess(true);
       	return $service_result ;
    }

    public function add_product_to_cart($product_id)
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $temp_service_result = $this->common_handle_member_cart_product($member_id,$product_id);
            if(!$temp_service_result->getSuccess()){
                return $temp_service_result;
            }

            $member_cart_order_id = $temp_service_result->getData()['member_cart_order_id'];
            $this->orderItemRepository->handle_order_items($product_id,$member_cart_order_id,$member_id);
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法執行加入購物車操作']);
            return $service_result ;
		}

		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function list_cart_order_items()
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
        $service_result_data = [];
		try{
			$this->begin_transaction();
            $is_empty_order_items = false;
            $query_result = $this->cartRepository->get_member_cart_order_items($member_id);
            $query_data = $query_result->getData();
            if(!$query_result->getSuccess()){
                $is_empty_order_items = true;
                if($query_data['member_not_exist']){
                    $this->rollback();
                    SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                    $service_result->setErrorMessage(["此會員帳號不存在，或是已被註銷"]);
                    return $service_result;
                }

                if($query_data['member_cart_order_id_is_null'] || $query_data['member_cart_order_not_exist']){
                    $member_cart_order_id = (int) $this->orderRepository->insert_order($member_id)->getData()['last_insert_order_id'];
                    $this->memberRepository->update_member_cart_order_id($member_id,$member_cart_order_id);
                }

            }else{
                $order_items = $query_data['member_cart_order_items'];
                $total = 0;
                foreach($order_items as $key=>$order_item){
                    try{
                        $order_items[$key]["csrf_token"] = CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$order_item['product_id']]);
                        $total = $total + intval($order_item['subtotal']);
                    }catch(\Exception | \Error $e){

                    }

                }

                $service_result_data['order_items'] = $order_items;
                $service_result_data['total'] = $total;
            }

            $service_result_data['empty_order_items'] = (bool) $is_empty_order_items;
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法取得購物車商品']);
            return $service_result ;
		}

        $service_result ->setData($service_result_data);
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function switch_cart_order_product_quantity($product_id,$query_count)
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $temp_service_result = $this->common_handle_member_cart_product($member_id,$product_id);
            if(!$temp_service_result->getSuccess()){
                return $temp_service_result;
            }

            $member_cart_order_id = $temp_service_result->getData()['member_cart_order_id'];
            $this->orderItemRepository->handle_order_items($product_id,$member_cart_order_id,$member_id,false,$query_count);
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法修改購物車商品數量']);
            return $service_result ;
		}

		$service_result ->setSuccess(true);
       	return $service_result ;
    }

    public function remove_cart_order_product($product_id)
    {
		$service_result  = new ResultData();
        $member_id = MemberUtility::get_login_member_id();
		try{
			$this->begin_transaction();
            $query_result = $this->cartRepository->get_member_cart_order($member_id);
            if(!$query_result->getSuccess()){
                $query_data = $query_result->getData();
                if($query_data['member_not_exist']){
                    $this->rollback();
                    SessionUtility::flash("member_not_exist",["此會員帳號不存在，或是已被註銷"]);
                    $service_result->setErrorMessage(["此會員帳號不存在，或是已被註銷"]);
                    return $service_result;
                }

                $member_cart_order_id = (int) $this->orderRepository->insert_order($member_id)->getData()['last_insert_order_id'];
                $this->memberRepository->update_member_cart_order_id($member_id,$member_cart_order_id);
            }else{
                $member_cart_order_id = (int) $query_result->getData()['member_cart_order']['id'];
                $this->orderItemRepository->remove_cart_order_item($product_id,$member_cart_order_id,$member_id);
            }

			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result ->setErrorMessage( ['資料庫連接失敗，無法將商品移出購物車']);
            return $service_result ;
		}
        
		$service_result ->setSuccess(true);
       	return $service_result ;
    }

}

?>