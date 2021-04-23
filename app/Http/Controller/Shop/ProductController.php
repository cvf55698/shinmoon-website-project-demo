<?php 

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Validate\FormValidateUtility;
use App\Auth\MemberAuth;
use App\Shinmoon\Traits\ControllerTrait;
use App\Page\PageProvider;
use App\Http\Param\ParamFilterUtility;
use App\Http\Csrf\CsrfUtility;
use App\Hash\HashUtility;

class ProductController{

    use ControllerTrait;

    public function list_product_of_category($product_category_id)
    {
        $request = request();
        $page = PageProvider::get_page($request);
        try{
            $product_category_id_int = ParamFilterUtility::get_int($product_category_id);
        }catch(\Exception | \Error $e){
            return view('shop/product/category-product-list-page',['category_name'=>'',],['商品種類不存在']);
        }

        $service_result = $this->productService->get_product_category($product_category_id_int);
        if(! $service_result->getSuccess()){
            return view('shop/product/category-product-list-page',['category_name'=>'',],$service_result->getErrorMessage());
        }
        
        $product_category = $service_result->getData()['product_category'];
        $category_name = $product_category['type_name'];
        $service_result = $this->productService->list_product_of_category($product_category_id_int,$page);
        if(!$service_result->getSuccess()){
            return view('shop/product/category-product-list-page',['category_name'=>$category_name,],$service_result->getErrorMessage());
        }

        $products = $service_result->getData()['products'];
        $page_data = $service_result->getData()['page_data'];
        return view('shop/product/category-product-list-page',['category_name'=>$category_name,'products'=>$products
            ,'page_data'=>$page_data,'product_category_id'=>$product_category_id]);
    }

    public function product_page($product_id)
    {
        try{
            $product_id = ParamFilterUtility::get_int($product_id);
        }catch(\Exception | \Error $e){
            return view('shop/product/product-page',[],['商品不存在']);
        }
        
        $service_result = $this->productService->get_product($product_id);
        if($service_result->getSuccess()){
            $product = $service_result->getData()['product'];
            $inventory_status = $service_result->getData()['inventory_status'];
            return view('shop/product/product-page',['product'=>$product,'inventory_status'=>$inventory_status,'member_has_login'=>MemberAuth::check()
                ,'cart_product_operate_hash'=>CsrfUtility::generate_token('member_operate_cart_product',['product_id'=>$product_id])]);
        }else{
            return view('shop/product/product-page',[],$service_result->getErrorMessage());
        }
    }

    public function product_search()
    {
        $request = request();
        $page = PageProvider::get_page($request);
        $keyword = $request->input('keyword');    
        $service_result = $this->productService->search_product($keyword,$page);
        if($service_result->getSuccess()){
            $data = $service_result->getData();
            return view('shop/product/product-search-page',['products'=>$data['products'],'origin_keyword'=>$keyword
                ,'origin_keyword_html_encode'=>HashUtility::html_encode($keyword),'page_data'=> $data['page_data']]);
        }else{
            return view('shop/product/product-search-page',['origin_keyword'=>$keyword
                ,'origin_keyword_html_encode'=>HashUtility::html_encode($keyword)],$service_result->getErrorMessage());
        }
    }

}

?>