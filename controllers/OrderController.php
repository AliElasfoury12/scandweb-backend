<?php

namespace app\controllers;

use app\models\Order;


class OrderController extends Controller {
    public static function handle($args) 
    {
        $id = rand(1,10000);

        $products = $args['products'];
        foreach ($products as &$product) {
            $product['id'] = $id;
        }

        Order::insertArr($products);
        
        return [
            "id" => $id,
            "products" => $products
        ];
    }
}