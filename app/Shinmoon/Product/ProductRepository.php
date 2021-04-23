<?php

namespace App\Shinmoon\Product;

use App\Shinmoon\Traits\RepositoryTrait;
use App\Result\ResultData;
use App\Page\PageProvider;

class ProductRepository{

    use RepositoryTrait;

    public function list_product_of_category($product_category_id,$page)
    {
        $query_page_data_result = PageProvider::get_page_query_data($page,'category',"product",["product_type_id = $product_category_id",]);
        if(!$query_page_data_result->getSuccess()){
            throw new \Exception();
        }

        $query_page_data = $query_page_data_result->getData();
        $per_page = $query_page_data['per_page'];
        $page_data = $query_page_data['page_data'];
        $offset = $query_page_data['offset'];

        $select_product_of_category_sql = "select * from product where product_type_id = :product_category_id order by id asc limit $per_page offset $offset ;";
        $query_result = $this->db->query($select_product_of_category_sql,[':product_category_id'=>(int)$product_category_id]);
        $products = [];
        if($query_result->getSuccess()){
            $products = $query_result->getData()['rows'];   
            return new ResultData(true,null,['products'=>$products,'page_data'=>$page_data]);
        }else{
            return new ResultData(false);
        }
        
    }

    public function select_product_by_keyword_search($keyword_arr,$page)
    {
        $where_clause_arr = [];
        foreach($keyword_arr as $keyword){
            array_push($where_clause_arr,"product_name like '%$keyword%'");
        }

        $query_page_data_result = PageProvider::get_page_query_data($page,'search',"product",$where_clause_arr);
        if(!$query_page_data_result->getSuccess()){
            throw new \Exception();
        }

        $query_page_data = $query_page_data_result->getData();
        $per_page = $query_page_data['per_page'];
        $page_data = $query_page_data['page_data'];
        $offset = $query_page_data['offset'];

        $select_product_sql = "select * from product where ".implode(" or ",$where_clause_arr)." order by id asc limit $per_page offset $offset ;";
        $query_result = $this->db->query($select_product_sql);
        $products = [];
        if($query_result->getSuccess()){
            $products = $query_result->getData()['rows'];   
            return new ResultData(true,null,['products'=>$products,'page_data'=>$page_data]);
        }else{
            return new ResultData(false);
        }

    }

    public function select_product_by_ranking()
    {
        $select_rank_products_sql = "select SUM(order_items.quantity) as total_quantity,product.* from order_items,product,orders "
            ."where order_items.product_id = product.id and orders.id = order_items.order_id and orders.has_commit = 1 "
            ."group by order_items.product_id order by total_quantity desc , order_items.product_id asc limit 10;"
            ;
        $query_result = $this->db->query($select_rank_products_sql);
        if($query_result->getSuccess()){
            $products = $query_result->getData()['rows'];   
            return new ResultData(true,null,['products'=>$products]);
        }else{
            return new ResultData(false);
        }

    }

    public function lock_products_by_order_items($order_items)
    {
        $product_id_arr = [];
        foreach($order_items as $order_item){
            array_push($product_id_arr,(int)$order_item['product_id']);
        }

        $select_products_sql = "select id from product where id in (".implode(",",$product_id_arr).") for update;";
        $this->db->exec($select_products_sql);
    }

    public function get_product_available($product_id)
    {
        $query_result = $this->select_product($product_id);
        if($query_result->getSuccess()){
            $product = $query_result->getData()['product'];
            $inventory = (int) $product['inventory'];
            $available = (int) $product['available'];
            if($available != 1){
                return new ResultData(false,['商品已停售，無法加入購物車']);
            }else if($inventory<=0){
                return new ResultData(false,['商品已完售，無法加入購物車']);
            }
            
            return new ResultData(true);
        }else{
            return new ResultData(false,['商品不存在']);
        }

    }

}

?>