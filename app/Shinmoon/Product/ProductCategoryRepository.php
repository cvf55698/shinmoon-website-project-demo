<?php

namespace App\Shinmoon\Product;

use App\Shinmoon\Traits\RepositoryTrait;
use App\Result\ResultData;

class ProductCategoryRepository{

    use RepositoryTrait;

    public function list_product_category()
    {
        $list_product_category_sql = "select * from product_type order by id asc;";
        $query_result = $this->db->query($list_product_category_sql);
        if($query_result->getSuccess()){
            $product_category_arr = $query_result->getData()['rows'];
            return new ResultData(true,null,['product_categorys'=>$product_category_arr]);
        }else{
            return new ResultData(false);
        }
        
    }

    public function select_product_category($product_category_id)
    {
        $select_product_category_sql = "select * from product_type where id = :id ;";
        $query_result = $this->db->query($select_product_category_sql,[':id'=>((int)$product_category_id)]);
        if($query_result->getSuccess()){
            $product_category = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['product_category'=>$product_category]);
        }else{
            return new ResultData(false);
        }

    }

}

?>