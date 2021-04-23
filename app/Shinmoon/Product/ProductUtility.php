<?php

namespace App\Shinmoon\Product;

use App\Result\ResultData;
use App\Hash\HashUtility;

class ProductUtility{

    public static function get_product_inventory_status($product)
    {
        if($product['available']){
            $inventory_count = (int) $product['inventory'];
            if($inventory_count>=10){
                $inventory_status = '立刻出貨';
            }else if($inventory_count>0){
                $inventory_status = '庫存<10';
            }else{
                $inventory_status = '已售完';
            }

        }else{
            $inventory_status = '已停售';
        }

        return $inventory_status;
    }

    public static function get_search_keyword_arr($keyword)
    {
        $get_search_keyword_arr_result = new ResultData();
        if($keyword==null){
            $keyword = '';
        }else{
            $keyword = trim($keyword);
        }

        if($keyword==''){
            $get_search_keyword_arr_result->setErrorMessage(['請輸入搜尋關鍵字']);
            return $get_search_keyword_arr_result;
        }

        $keyword_arr = [];
        $get_keyword_arr = explode(' ',$keyword);
        foreach($get_keyword_arr as $get_keyword){
            $get_keyword_encode = HashUtility::html_encode($get_keyword);
            if( ($get_keyword_encode!='') && (!in_array($get_keyword_encode,$keyword_arr))  ){
                array_push($keyword_arr,$get_keyword_encode);
            }
            
        }

        if(count($keyword_arr)==0){
            $get_search_keyword_arr_result->setErrorMessage(['請輸入搜尋關鍵字']);
            return $get_search_keyword_arr_result;
        }

        $get_search_keyword_arr_result->setSuccess(true);
        $get_search_keyword_arr_result->setData(['keyword_arr'=>$keyword_arr]);
        return $get_search_keyword_arr_result;
    }

}

?>