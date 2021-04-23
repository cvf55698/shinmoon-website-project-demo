<?php

namespace App\Shinmoon\Order;

use App\Database\DatabaseUtility;
use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\RepositoryTrait;

class OrderItemRepository{

    use RepositoryTrait;

    public function select_order_item($product_id,$order_id)
    {
        $select_order_item_sql = "select * from order_items where product_id = :product_id and order_id = :order_id ;";
        $query_result = $this->db->query($select_order_item_sql,[':product_id'=>$product_id,':order_id'=>$order_id]);
        if($query_result->getSuccess()){
            $order_item = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['order_item'=>$order_item]);
        }else{
            return new ResultData(false);
        }
    }

    public function insert_order_item($product,$order_id,$quantity)
    {
        $insert_order_item_sql = "insert into order_items (product_id,order_id,unit_price,quantity,subtotal) values "
            ."(:product_id,:order_id,:unit_price,:quantity,:subtotal) ;";   
        $order_id = (int) $order_id;
        $product_id = (int) $product['id'];
        $unit_price = (int) $product['price'];
        $quantity = (int) $quantity;
        $subtotal = $unit_price  * $quantity;
        $this->db->exec($insert_order_item_sql,[':product_id'=>$product_id,':order_id'=>$order_id,':unit_price'=>$unit_price,':quantity'=>$quantity,':subtotal'=>$subtotal]);
    }

    public function update_order_item_by_add($product,$order_id)
    {
        $update_sql = "update order_items set unit_price = :unit_price , quantity = quantity + 1 , subtotal = unit_price * (quantity) "
            ."where product_id = :product_id and order_id = :order_id ;";
        $order_id = (int) $order_id;
        $product_id = (int) $product['id'];
        $unit_price = (int) $product['price'];
        $this->db->exec($update_sql,[':product_id'=>$product_id,':order_id'=>$order_id,':unit_price'=>$unit_price]);
    }

    public function update_order_item_by_set($product,$order_id,$quantity)
    {
        $update_sql = "update order_items set unit_price = :unit_price , quantity = :quantity , subtotal = :subtotal "
            ."where product_id = :product_id and order_id = :order_id ;";
        $order_id = (int) $order_id;
        $product_id = (int) $product['id'];
        $unit_price = (int) $product['price'];
        $quantity = (int) $quantity;
        $subtotal = $unit_price  * $quantity;
        $this->db->exec($update_sql,[':product_id'=>$product_id,':order_id'=>$order_id,':unit_price'=>$unit_price,':quantity'=>$quantity,':subtotal'=>$subtotal]);
    }
    
    public function delete_order_item($product_id,$order_id)
    {
        $delete_order_item_sql = "delete from order_items where product_id = :product_id and order_id = :order_id ;";
        $this->db->exec($delete_order_item_sql,[':product_id'=>$product_id,':order_id'=>$order_id]);
    }

    public function lock_order_item($product_id,$order_id)
    {
        $lock_order_item_sql = "select product_id,order_id from order_items where product_id = :product_id and order_id = :order_id for update;";
        $this->db->exec($lock_order_item_sql,[':product_id'=>$product_id,':order_id'=>$order_id]);
    }

    public function lock_order_items($order_items_arr)
    {
        foreach($order_items_arr as $order_item){
            $this->lock_order_item((int)$order_item['product_id'],(int)$order_item['order_id']);
        }
    }

    public function handle_order_items($product_id,$member_cart_order_id,$member_id,$is_add = true,$query_count = 1)
    {
        $query_result = $this->select_product($product_id);
        if(!$query_result->getSuccess()){
            $this->remove_cart_order_item($product_id,$member_cart_order_id,$member_id);
            return ;
        }

        $product = $query_result->getData()['product'];
        if($this->select_order_item($product_id,$member_cart_order_id)->getSuccess()){
            if($is_add){
                $this->update_order_item_by_add($product,$member_cart_order_id);
            }else{
                $this->update_order_item_by_set($product,$member_cart_order_id,$query_count);
            }

        }else{
            $this->insert_order_item($product,$member_cart_order_id,$query_count);
        }
        
    }

    public function remove_cart_order_item($product_id,$member_cart_order_id,$member_id)
    {
        $this->delete_order_item($product_id,$member_cart_order_id);
        $query_result = $this->list_order_item($member_cart_order_id);
        if(!$query_result->getSuccess()){
            $this->clear_member_cart_order_id($member_id);
            $this->delete_order($member_cart_order_id);
        }

    }

    public function commit_order_items($order_id)
    {
        $update_order_items_product_quantity_sql = "update product,order_items set product.inventory = product.inventory - order_items.quantity "
            ."where order_items.order_id = :order_id and order_items.product_id = product.id;";
        $this->db->exec($update_order_items_product_quantity_sql,[':order_id'=>$order_id]);
        $query_product_inventory_sql = "select order_items.*,product.product_name,".
            "CASE product.inventory >= 0 WHEN true THEN 1 ELSE 0 END AS enough_to_buy,".
            "product.available,product.inventory from order_items,product where order_id = :order_id and order_items.product_id = product.id order by product.id asc;";
        $query_result = $this->db->query($query_product_inventory_sql,[':order_id'=>$order_id]);
        if(!$query_result->getSuccess()){
            throw new \Exception();
        }

        $product_arr = $query_result->getData()['rows'];
        $commit_order_items_error_arr = ['error_arr'=>[],'drop_order_items_products'=>[]];
        foreach($product_arr as $product){
            $inventory = (int) $product['inventory'];
            $quantity = (int) $product['quantity'];
            $available = (int) $product['available'];
            $enough_to_buy = (int) $product['enough_to_buy'];
            $product_name = $product['product_name'];
            $product_id = $product['product_id'];
            if($available!=1){
                array_push($commit_order_items_error_arr['drop_order_items_products'],$product_id);
                array_push($commit_order_items_error_arr['error_arr'],"[$product_name] 商品已停售");
            }else if($enough_to_buy!=1){
                array_push($commit_order_items_error_arr['error_arr'],"[$product_name] 商品庫存件數為 ".($inventory+$quantity)." , 不足購買");
            }

        }

        if(count($commit_order_items_error_arr['error_arr'])!=0){
            return new ResultData(false,null,$commit_order_items_error_arr);
        }

        return new ResultData(true);
    }

}

?>