<?php

namespace App\Shinmoon\Traits;

use App\Shinmoon\Member\MemberService;
use App\Shinmoon\Product\ProductService;
use App\Shinmoon\Cart\CartService;
use App\Shinmoon\Order\OrderService;

trait ControllerTrait{

    private $memberService;
    private $productService;
    private $cartService;
    private $orderService;

    public function __construct()
    {
        $this->memberService = MemberService::getInstance();
        $this->productService = ProductService::getInstance();
        $this->cartService = CartService::getInstance();
        $this->orderService = OrderService::getInstance();
    }

}

?>