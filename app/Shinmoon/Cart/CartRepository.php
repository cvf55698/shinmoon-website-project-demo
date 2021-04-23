<?php

namespace App\Shinmoon\Cart;

use App\Database\DatabaseUtility;
use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\RepositoryTrait;

class CartRepository{

    use RepositoryTrait;

    public function get_member_cart_order_id_by_id($member_id)
    {
        $this_query_result  = new ResultData();
        $query_data = [];
        $query_result = $this->select_member_by_id($member_id);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['member'];
            if($member['cart_order_id'] == null){
                return new ResultData(false,null,['empty_order_items'=>true,'member_cart_order_id_is_null'=>true,'member_not_exist'=>false]);
            }else{
                return new ResultData(true,null,['member_cart_order_id'=>(int) $member['cart_order_id'],'member_not_exist'=>false,'empty_order_items'=>false]);
            }

        }else{
            return new ResultData(false,null,['empty_order_items'=>true,'member_not_exist'=>true]);
        }

    }

    public function get_member_cart_order($member_id)
    {
        $query_result = $this->get_member_cart_order_id_by_id($member_id);
        if(!$query_result->getSuccess()){
            return $query_result;
        }

        $query_data = $query_result->getData();
        $member_cart_order_id = (int) $query_data['member_cart_order_id'];
        $query_result = $this->select_order($member_cart_order_id);
        if($query_result->getSuccess()){
            $query_data['member_cart_order_not_exist'] = false;
            $query_data['empty_order_items'] = false;
            $query_data['member_cart_order'] = $query_result->getData()['order'];
            return new ResultData(true,null,$query_data);
        }else{
            $query_data['member_cart_order_not_exist'] = true;
            $query_data['empty_order_items'] = true;
            return new ResultData(false,null,$query_data);
        }

    }

    public function get_member_cart_order_items($member_id)
    {
        $query_result = $this->get_member_cart_order($member_id);
        if(!$query_result->getSuccess()){
            return $query_result;
        }

        $query_data = $query_result->getData();
        $member_cart_order_id = (int) $query_data['member_cart_order_id'];
        $query_result = $this->list_order_item($member_cart_order_id);
        if($query_result->getSuccess()){
            $query_data['empty_order_items'] = false;
            $query_data['member_cart_order_items'] = $query_result->getData()['order_items'];
            return new ResultData(true,null,$query_data);
        }else{
            $query_data['empty_order_items'] = true;
            return new ResultData(false,null,$query_data);
        }
        
    }

}

?>