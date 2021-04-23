<?php

namespace App\Shinmoon\Order;

use App\Database\DatabaseUtility;
use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Order\OrderUtility;
use App\Shinmoon\Traits\RepositoryTrait;
use App\Page\PageProvider;

class OrderRepository{

    use RepositoryTrait;

    public function list_member_history_orders($member_id,$page)
    {
        $query_page_data_result = PageProvider::get_page_query_data($page,'order',"orders",["has_commit = 1","member_id = $member_id"]);
        if(!$query_page_data_result->getSuccess()){
            throw new \Exception();
        }

        $query_page_data = $query_page_data_result->getData();
        $per_page = $query_page_data['per_page'];
        $page_data = $query_page_data['page_data'];
        $offset = $query_page_data['offset'];

        $select_orders_sql = "select * from orders where has_commit = 1 and member_id = :member_id order by id desc limit $per_page offset $offset ;";
        $query_result  = $this->db->query($select_orders_sql,[':member_id'=>$member_id,]);
        if($query_result->getSuccess()){
            return new ResultData(true,null,['orders'=>($query_result->getData()['rows']),'page_data'=>$page_data]);
        }else{
            return new ResultData(false);
        }
    }

    public function insert_order($member_id)
    {
        $insert_order_sql = "insert into orders (member_id) values (:member_id) ;";
        $this->db->exec($insert_order_sql,[':member_id'=>$member_id]);
        return new ResultData(true,null,['last_insert_order_id'=>(int)$this->db->get_last_insert_id()]);
    }


    public function update_order_checkout_setting($order_id,$param_arr)
    {
        $set_param_arr = OrderUtility::get_update_order_setting_cluase_arr_from_param_arr($param_arr);
        $update_order_sql = "update orders set ".implode(" , ",$set_param_arr)." where id = :order_id ;";
        $this->db->exec($update_order_sql ,[':order_id'=>$order_id]);
    }

    public function lock_order($order_id)
    {
        $select_order_sql = "select id from orders where id = :order_id for update;";
        $this->db->exec($select_order_sql,[':order_id'=>$order_id]);
    }

    public function get_member_history_order($order_id,$member_id)
    {
        $select_order_sql = "select * from orders where id = :order_id and has_commit = 1 and member_id = :member_id ;";
        $query_result  = $this->db->query($select_order_sql,[':order_id'=>(int)$order_id,':member_id'=>(int)$member_id]);
        if(!$query_result->getSuccess()){
            return new ResultData(false);
        }

        $order = $query_result->getData()['rows'][0];
        $query_result = $this->list_order_item($order_id);
        if(!$query_result->getSuccess()){
            return new ResultData(false);
        }

        $order_items = $query_result->getData()['order_items'];
        $invoice_info = OrderUtility::get_invoice_info($order);
        return new ResultData(true,null,['order'=>$order,'order_items'=>$order_items,'invoice_info'=>$invoice_info,]);
    }

    public function update_order_total($order_id)
    {
        $query_result = $this->list_order_item($order_id);
        if(!$query_result->getSuccess()){
            return;
        }

        $total = 0;
        $order_items = $query_result->getData()['order_items'];
        foreach($order_items as $order_item){
            $total += (int) $order_item['subtotal'];
        }

        $update_order_total_sql = "update orders set total = shipping_fee + :total where id = :order_id ;";
        $this->db->exec($update_order_total_sql,[':order_id'=>$order_id,':total'=>$total]);
    }

    public function commit_order($order_id)
    {
        $commit_order_sql = "update orders set 	has_commit = 1 , order_time = '".date("Y/m/d H:i:s")."' where id = :order_id ;";
        $this->db->exec($commit_order_sql,[':order_id'=>$order_id]);
    }
    
}

?>