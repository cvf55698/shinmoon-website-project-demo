<!doctype html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">


        <link href="/css/base.css" rel="stylesheet" >
        {{block name=head_css}}{{/block}}
        
        <title>{{block name=title}}{{php}}$app_config = require CONFIG_PATH."app.php";echo $app_config['site_name'];{{/php}}{{/block}}</title> 
    </head>
    <body style="background-color: #C0C0C0;">
        

        <a id="commodity-type-list-toggle-launcher" style="opacity:0;position:absolute;left:20px;top:5px;z-index:20;" class="btn btn-primary" data-bs-toggle="offcanvas" href="#commodity-type-list-offcanvas" role="button" aria-controls="offcanvasExample">
             &nbsp;&nbsp;
        </a>

        
        {{php}}  
            if(App\Auth\MemberAuth::check()){
		{{/php}}  
        <a id="member-option-li-md-toggle-launcher" style="opacity:0;position:absolute;right:80px;top:5px;z-index:20;" class="btn btn-primary" data-bs-toggle="offcanvas" href="#menu-option-offcanvas" role="button" aria-controls="offcanvasExample">
            &nbsp;&nbsp;
        </a>
        {{php}}  
            }
		{{/php}} 

        <nav id="header-navbar" class="navbar navbar-expand navbar-dark bg-dark">
            <div id="header-container" class="container-lg">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="nav navbar-nav ml-auto">
                        <li id="commodity-type-list-toggle" class="nav-item">
                            <a class="nav-link active"  href="#">
                                <i  class="fas fa-book"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav ms-auto">

                        
                        {{php}}  
                            if(App\Auth\MemberAuth::check()){
		                {{/php}}  
                        
                        <li id="member-option-li-lg" class="nav-item">
                            <div class="dropdown">
                                <a id="member-option-dropdown-menu-a" class="nav-link active" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i><span>&nbsp;&nbsp;會員專區</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="member-option-dropdown-menu-a">
                                    <li><a class="dropdown-item" href="/orders">歷史訂單列表</a></li>
                                    <li><a class="dropdown-item" href="/member/edit">修改個人資料</a></li>
                                    <li><a class="dropdown-item" href="/email/edit">修改email</a></li>
                                    <li><a class="dropdown-item" href="/password/edit">修改密碼</a></li>
                                    <li><a class="dropdown-item" href="/logout">登出</a></li>
                                </ul>
                            </div>
                        </li>

                        <li id="member-option-li-md" class="nav-item">
                            <a class="nav-link active">
                                <i class="fas fa-user"></i><span>&nbsp;&nbsp;會員專區</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="/cart" role="button"><i class="fas fa-cart-plus"></i><span>&nbsp;&nbsp;購物車</span></a>
                        </li>
                        

                        {{php}}  
                            }else{
		                {{/php}}  
                        <li  class="nav-item">
                            <a class="nav-link active" href="/login">
                                <i class="fas fa-user"></i><span>&nbsp;&nbsp;登入</span>
                            </a>
                        </li>

                        <li  class="nav-item">
                            <a class="nav-link active" href="/register">
                                <i class="fas fa-user-plus"></i><span>&nbsp;&nbsp;註冊</span>
                            </a>
                        </li>
                        
                        {{php}}  
                            }
		                {{/php}}  
                    </ul>
                </div>
            </div>
        </nav>


        <div id="main-block" class="container-xl" style="min-height:500px;background-color: transparent;">
            <div class="row">
                <div id="index-block" class="col-0 col-lg-3 col-xl-2 d-none d-lg-block" style="position: relative;min-height:350px;padding: 0px;margin: 0px;">

                    <div style="background-color: #FFDEAD;" class="row content-row">
                        <a href="/" style="text-decoration:none;color:black;">
                            <div style="position: relative;margin:0px;" class="col-12 d-flex justify-content-center">
                                <img id="logo-img" src="/images/logo.png" />
                            </div>                        
                            <div style="position: relative;margin:0px;top:0px;min-height: 100px;" class="col-12 d-flex justify-content-center">
                                <h2 style="position: relative;top:20px;">
                                    {{php}}
                                    $app_config = require CONFIG_PATH."app.php";
                                    echo $app_config['site_name'];
                                    {{/php}}
                                </h2>
                            </div>
                        </a>
                    </div>

                    <div style="height: 60px;" class="row content-row">

                    </div>

                    <div style="background-color: white;" class="row content-row">
                        <div style="height:70px;" class="d-flex justify-content-center content-row">
                            <h4 style="position: relative;top:20px;">商品種類</h4>
                        </div>
                        <div class="list-group list-group-primary" style="width:100%;margin:0px;position: relative;padding: 0px;">
                            {{php}}
                                $list_product_category_result = App\Shinmoon\Product\ProductService::getInstance()->list_product_category();
                                if($list_product_category_result->getSuccess()){
                                    foreach($list_product_category_result->getData()['product_category_arr'] as $product_category){
                                        {{/php}}
                                            <a href="/product/category/{{php}}echo $product_category['id'];{{/php}}" class="list-group-item list-group-item-action">
                                                {{php}}echo $product_category['type_name'];{{/php}}
                                            </a>
                                        {{php}}
                                    }
                                }
                            {{/php}}
                        </div>
                        
                    </div>

                </div>
                
                <div class="col-12 col-lg-9 col-xl-10" >
                    
                    <div style="min-height: 60px;position: relative;top:-10px;padding-left: 15px;" class="row content-row">
                        <form id="product-search-form" style="position: relative;" class="row g-3" method="get" action="/search">
                            <div class="col-12 col-md-auto">
                                <input type="text" name="keyword" style="max-width:300px;height:40px;font-size:18px;font-weight:bold;" class="form-control" id="search-text-input" 
                                    placeholder="" value="{{$origin_keyword}}">
                            </div>
                            <div class="col-12 col-md-auto">
                                <button type="submit" style="width:100px;font-size:18px;height:40px;" class="btn btn-primary mb-3"><i class="fas fa-search"></i>&nbsp;&nbsp;搜尋</button>
                            </div>
                        </form>
                    </div>

                    <div id="pop-message-block" style="width:95%;left:2.5%;border-radius:10px;" class="content-row">
                        {{foreach $error_message_arr as $error_message}}
                            <div class="alert alert-danger" role="alert">
                                {{$error_message}}
                            </div>
                        {{/foreach}}

                        {{foreach $success_message_arr as $success_message}}
                            <div class="alert alert-success" role="alert">
                            {{$success_message}}
                            </div>
                        {{/foreach}}

                        {{foreach $warning_message_arr as $warning_message}}
                            <div class="alert alert-warning" role="alert">
                                {{$warning_message}}
                            </div>
                        {{/foreach}}
                    </div>

                    <div style="height: 20px;" class="row content-row">

                    </div>


                    <div id="content-block"  class="content-row">

                        {{block name=content_block}}{{/block}}

                    </div>

                </div>

            </div>

        </div>
      

		<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="commodity-type-list-offcanvas" aria-labelledby="commodity-type-list-offcanvas-label">
		    <div class="offcanvas-header" style="color:white;">
		        <h2 style="position: relative;top:20px;" class="offcanvas-title" id="commodity-type-list-offcanvas-label">商品種類</h5>
		        <button type="button" style="background-color: white;" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		    </div>
		    <div class="offcanvas-body" style="width:100%;padding:0px;">
		        <div class="list-group list-group-primary" style="width:100%;margin:0px;position: relative;top:50px;">
                            {{php}}
                                $list_product_category_result = App\Shinmoon\Product\ProductService::getInstance()->list_product_category();
                                if($list_product_category_result->getSuccess()){
                                    foreach($list_product_category_result->getData()['product_category_arr'] as $product_category){
                                        {{/php}}
                                            <a href="/product/category/{{php}}echo $product_category['id'];{{/php}}" class="list-group-item list-group-item-action">
                                                {{php}}echo $product_category['type_name'];{{/php}}
                                            </a>
                                        {{php}}
                                    }
                                }
                            {{/php}}
		        </div>
		    </div>
		</div>
		
        {{php}}  
            if(App\Auth\MemberAuth::check()){
		{{/php}}  
		<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="menu-option-offcanvas" aria-labelledby="menu-option-offcanvas-label">
		    <div class="offcanvas-header" style="color:white;">
		        <h2 style="position: relative;top:20px;" class="offcanvas-title" id="menu-option-offcanvas-label">會員專區</h5>
		        <button type="button" style="background-color: white;" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		    </div>
		    <div class="offcanvas-body" style="width:100%;padding:0px;">
		        <div class="list-group list-group-primary" style="width:100%;margin:0px;position: relative;top:50px;">
		            <a href="/orders" class="list-group-item list-group-item-action">歷史訂單列表</a>
		            <a href="/member/edit" class="list-group-item list-group-item-action">修改個人資料</a>
		            <a href="/email/edit" class="list-group-item list-group-item-action">修改email</a>
		            <a href="/password/edit" class="list-group-item list-group-item-action">修改密碼</a>
		            <a href="/logout" class="list-group-item list-group-item-action">登出</a>
		        </div>
		    </div>
		</div>
        {{php}}  
            }
		{{/php}}  


        <div class="modal fade" id="loading-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div style="position:absolute;left:50%;width:50%;margin-left:-25%;" class="modal-content">
                    <div class="modal-body">
                        <h5 style="float:left;" class="modal-title" id="exampleModalLabel">更新中</h5>
                        <div style="float:right;" class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="message-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close close-modal-button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-modal-button" data-bs-dismiss="modal">關閉</button>
                    </div>
                </div>
            </div>
        </div>


        
        <script src="https://kit.fontawesome.com/8b4677d803.js" crossorigin="anonymous"></script>
        
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
        <script src="/javascript/base.js"> </script>
        {{block name=js}}{{/block}}
        <script src="/javascript/base-last.js"> </script>
    </body>
</html>