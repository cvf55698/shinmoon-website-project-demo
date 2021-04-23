<?php

namespace App\Shinmoon\Traits;

use App\Database\DatabaseUtility;
use App\Shinmoon\Member\MemberRepository;
use App\Shinmoon\Product\ProductRepository;
use App\Shinmoon\Product\ProductCategoryRepository;
use App\Shinmoon\Cart\CartRepository;
use App\Shinmoon\Order\OrderRepository;
use App\Shinmoon\Order\OrderItemRepository;
use App\Result\ResultData;

trait RepositoryTrait{

    private static $instance;
    private $db;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
   
    private function __construct()
    {
        $this->db = DatabaseUtility::getInstance();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function select_member_by_id($id)
    {
        $select_member_sql = "select * from member where id = :id ;";
        $query_result = $this->db->query($select_member_sql,[':id'=>$id,]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function clear_member_cart_order_id($member_id)
    {
        $clear_member_cart_order_id_sql = "update member set cart_order_id = NULL where id = :id ;";
        $this->db->exec($clear_member_cart_order_id_sql,[':id'=>$member_id]);
    }

    public function select_product($product_id)
    {
        $select_product_sql = "select * from product where id = :id ;"; 
        $query_result = $this->db->query($select_product_sql,[':id'=>$product_id]);
        if($query_result->getSuccess()){
            $product = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['product'=>$product]);
        }else{
            return new ResultData(false);
        }
    }

    public function list_order_item($order_id)
    {
        $list_order_item_sql = "select *,product.product_name,product.main_image from order_items,product where order_id = :order_id "
            ."and order_items.product_id = product.id order by product.id asc;";
        $query_result = $this->db->query($list_order_item_sql,[":order_id"=>$order_id]);
        if($query_result->getSuccess()){
            $order_items = $query_result->getData()['rows'];
            return new ResultData(true,null,['order_items'=>$order_items]);
        }else{
            return new ResultData(false);
        }
    }

    public function select_order($order_id)
    {
        $select_order_sql = "select orders.*,svg.name as svg_name from orders left join svg on svg.id = orders.svg_id where orders.id = :order_id ;";
        $query_result = $this->db->query($select_order_sql,[':order_id'=>$order_id,]);
        if($query_result->getSuccess()){
            $order = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['order'=>$order]);
        }else{
            return new ResultData(false);
        }
    }

    public function delete_order($order_id)
    {
        $delete_order_sql = "delete from orders where id = :order_id ;";
        $this->db->exec($delete_order_sql,[':order_id'=>$order_id]);
    }

}

?>