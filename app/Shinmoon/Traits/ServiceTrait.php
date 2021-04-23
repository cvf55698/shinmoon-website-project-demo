<?php

namespace App\Shinmoon\Traits;

use App\Shinmoon\Member\MemberRepository;
use App\Shinmoon\Product\ProductRepository;
use App\Shinmoon\Product\ProductCategoryRepository;
use App\Shinmoon\Cart\CartRepository;
use App\Shinmoon\Order\OrderRepository;
use App\Shinmoon\Order\OrderItemRepository;
use App\Database\DatabaseUtility;

trait ServiceTrait{

    private static $instance;
	private $memberRepository;
    private $productRepository;
    private $productCategoryRepository;
    private $cartRepository;
    private $orderRepository;
    private $orderItemRepository;
    private $db;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    
    private function __construct()
    {
        $this->memberRepository = MemberRepository::getInstance();
        $this->productRepository = ProductRepository::getInstance();
        $this->productCategoryRepository = ProductCategoryRepository::getInstance();
        $this->cartRepository = CartRepository::getInstance();
        $this->orderRepository = OrderRepository::getInstance();
        $this->orderItemRepository = OrderItemRepository::getInstance();
        $this->db = DatabaseUtility::getInstance();
    }
    

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public function get_last_insert_id()
    {
        return $this->db->get_last_insert_id();
    }

    public function begin_transaction()
    {
        $this->db->begin_transaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
    }

    public function get_inTransaction():bool
    {
        return $this->db->get_inTransaction();
    }
   
}

?>