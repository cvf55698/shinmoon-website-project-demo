<?php

namespace App\Shinmoon\Product;

use App\Shinmoon\Traits\ServiceTrait;
use App\Result\ResultData;
use App\Page\PageProvider;
use App\Shinmoon\Product\ProductUtility;

class ProductService{

    use ServiceTrait;

    public function list_product_category()
    {
		$service_result = new ResultData();
		try{
			$this->begin_transaction();
            $query_result = $this->productCategoryRepository->list_product_category();
            if(!$query_result->getSuccess()){
                $this->rollback();
			    $service_result->setErrorMessage( ['資料庫連接失敗，無法取得產品種類列表']);
                return $service_result;
            }

            $product_category_arr = $query_result->getData()["product_categorys"];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法取得產品種類列表']);
            return $service_result;
		}

		$service_result->setSuccess(true);
        $service_result->setData(['product_category_arr'=>$product_category_arr]);
       	return $service_result;
    }

    public function get_product_category($product_category_id)
    {
        $service_result = new ResultData();
		try{
			$this->begin_transaction();
            $query_result = $this->productCategoryRepository->select_product_category($product_category_id);
            if(!$query_result->getSuccess()){
                $this->rollback();
			    $service_result->setErrorMessage( ['商品種類不存在']);
                return $service_result;
            }

            $product_category = $query_result->getData()['product_category'];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法取得商品種類']);
            return $service_result;
		}
        
        $service_result->setData(['product_category'=>$product_category]);
		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function list_product_of_category($product_category_id,$page)
    {
        $service_result = new ResultData();    
		try{
			$this->begin_transaction();
            $product_category_id = (int) $product_category_id;
            $query_result = $this->productCategoryRepository->select_product_category($product_category_id);
            if(!$query_result->getSuccess()){
                $this->rollback();
			    $service_result->setErrorMessage( ['商品種類不存在']);
                return $service_result;
            }
            
            $query_result = $this->productRepository->list_product_of_category($product_category_id,$page);
            $products = [];
            if($query_result->getSuccess()){
                $products = $query_result->getData()['products'];
            }

            $page_data = $query_result->getData()['page_data'];
            $this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法取得指定種類商品列表']);
            return $service_result;
		}

        $service_result->setData(['products'=>$products,'page_data'=>$page_data,]);
		$service_result->setSuccess(true);
       	return $service_result;			
    }

    public function get_product($product_id)
    {
		$service_result = new ResultData();
		try{
			$this->begin_transaction();
            $query_result = $this->productRepository->select_product($product_id);
            if(!$query_result->getSuccess()){
                $this->rollback();
			    $service_result->setErrorMessage( ['商品不存在']);
                return $service_result;
            }

            $product = $query_result->getData()['product'];
            $inventory_status = ProductUtility::get_product_inventory_status($product);
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法取得指定商品']);
            return $service_result;
		}

        $service_result->setData(['product'=>$product,'inventory_status'=>$inventory_status,]);
		$service_result->setSuccess(true);
       	return $service_result;        
    }

    public function search_product($keyword,$page)
    {
        $service_result = new ResultData();
		try{
			$this->begin_transaction();
            $get_search_keyword_arr_result = ProductUtility::get_search_keyword_arr($keyword);
            if(!$get_search_keyword_arr_result->getSuccess()){
                $this->rollback();
                return $get_search_keyword_arr_result;
            }

            $keyword_arr = $get_search_keyword_arr_result->getData()['keyword_arr'];
            $query_result = $this->productRepository->select_product_by_keyword_search($keyword_arr,$page);
            $products = [];
            if($query_result->getSuccess()){
                $products = $query_result->getData()['products'];
            }

            $page_data = $query_result->getData()['page_data'];
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法搜尋商品關鍵字']);
            return $service_result;
		}

        $service_result->setData(['products'=>$products,'page_data'=>$page_data,]);
		$service_result->setSuccess(true);
       	return $service_result;
    }

    public function get_rank_products()
    {
		$service_result = new ResultData();
		try{
			$this->begin_transaction();       
            $query_result = $this->productRepository->select_product_by_ranking();
            $products = [];
            if($query_result->getSuccess()){
                $products = $query_result->getData()['products'];
            }
            
			$this->commit();
		}catch(\Error | \Exception $e){
			$this->rollback();
			$service_result->setErrorMessage( ['資料庫連接失敗，無法抓取商品排行']);
            return $service_result;
		}

        $service_result->setData(['products'=>$products]);
		$service_result->setSuccess(true);
       	return $service_result;
    }
    
}

?>