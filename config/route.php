<?php

return [
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/$/",
        "URL_FUNCTION"=> "Shop.HomeController@home",  
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/register$/",
        "URL_FUNCTION"=> "Shop.MemberController@register_page",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/register$/",
        "URL_FUNCTION"=> "Shop.MemberController@register",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
        "CSRF"=>'member_register',
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/login$/",
        "URL_FUNCTION"=> "Shop.MemberController@login_page",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/login$/",
        "URL_FUNCTION"=> "Shop.MemberController@login",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
        "CSRF"=>'member_login',
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/logout$/",
        "URL_FUNCTION"=> "Shop.MemberController@logout",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/password\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@update_password_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/password\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@update_password",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
        "CSRF"=>'member',
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/email\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@update_email_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/email\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@update_email",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
        "CSRF"=>"member",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/password\/new$/",
        "URL_FUNCTION"=> "Shop.MemberController@forget_password_email_page",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/password\/new$/",
        "URL_FUNCTION"=> "Shop.MemberController@forget_password_email",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
        "CSRF"=>'member_send_new_password_email_page',
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/password$/",
        "URL_FUNCTION"=> "Shop.MemberController@forget_password_page",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/password$/",
        "URL_FUNCTION"=> "Shop.MemberController@forget_password",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
        "CSRF"=>"member_send_new_password_page",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/member\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@member_edit_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/member\/edit$/",
        "URL_FUNCTION"=> "Shop.MemberController@member_edit",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
        "CSRF"=>"member",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/facebook\/callback$/",
        "URL_FUNCTION"=> "Shop.OauthController@facebook_callback",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/google\/callback$/",
        "URL_FUNCTION"=> "Shop.OauthController@google_callback",
        "FILTER"=>[
            'MemberNotLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/product\/category\/([1-9][0-9]{0,})$/",
        "URL_FUNCTION"=> "Shop.ProductController@list_product_of_category",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/product\/([1-9][0-9]{0,})$/",
        "URL_FUNCTION"=> "Shop.ProductController@product_page",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=>"/^\/search$/",
        "URL_FUNCTION"=> "Shop.ProductController@product_search",
    ] ,
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=>"/^\/cart\/add$/",
        "URL_FUNCTION"=> "Shop.CartController@add_product_to_cart",
    ] ,
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/cart$/",
        "URL_FUNCTION"=> "Shop.CartController@cart_edit_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/cart\/edit$/",
        "URL_FUNCTION"=> "Shop.CartController@switch_cart_product_quantity",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/cart\/delete$/",
        "URL_FUNCTION"=> "Shop.CartController@delete_cart_product",
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/orders\/new$/",
        "URL_FUNCTION"=> "Shop.OrderController@order_setting_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/orders\/new$/",
        "URL_FUNCTION"=> "Shop.OrderController@order_setting",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
        "CSRF"=>"member"
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/orders\/submit$/",
        "URL_FUNCTION"=> "Shop.OrderController@order_commit_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_POST),
        "URL_PATTERN"=> "/^\/orders\/submit$/",
        "URL_FUNCTION"=> "Shop.OrderController@order_commit",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
        "CSRF"=>"member"
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/orders$/",
        "URL_FUNCTION"=> "Shop.OrderController@orders_list_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
    [
        "HTTP_METHOD"=> array(HTTP_METHOD_GET),
        "URL_PATTERN"=> "/^\/orders\/([1-9][0-9]{0,})$/",
        "URL_FUNCTION"=> "Shop.OrderController@orders_page",
        "FILTER"=>[
            'MemberHasLoginFilter',
        ],
    ],
];

?>
