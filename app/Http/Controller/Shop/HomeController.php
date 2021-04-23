<?php

namespace App\Http\Controller\Shop;

use App\Session\SessionUtility;
use App\Shinmoon\Traits\ControllerTrait;

class HomeController{

    use ControllerTrait;

    public function home()
    {
        $success_message = [];
        if(SessionUtility::flash("login_success_message")){
            $success_message = SessionUtility::flash("login_success_message");
        }else if(SessionUtility::flash("logout_success_message")){
            $success_message = SessionUtility::flash("logout_success_message");
        }
        
        $service_result = $this->productService->get_rank_products();
        if($service_result->getSuccess()){
            return view('shop/home-page',['products'=>$service_result->getData()['products'],],[],$success_message);
        }else{
            return view('shop/home-page',[],$service_result->getErrorMessage());
        }
    }

}

?>