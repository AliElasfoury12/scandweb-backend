<?php

namespace app\database\seeder;

use app\core\App;
use app\core\database\DB;

class ItemsSeeder extends Seeder {
  
    public static function seed ($products) 
    {
        $itmesItem = $products[0]['attributes'][0]['items'][0];
        $columns = array_keys($itmesItem); 
        $result = [];

        foreach ($products as $product) {
            if(!$product['attributes']){
               continue;
            }

            $attributes = $product['attributes'];
            $productId = $product['id'];

            foreach ($attributes as $attribute) {
                $items = $attribute['items'];
                foreach ($items as &$item) {
                    $item['product_id'] = $productId;  
                    $item['attribute_id'] = $attribute['id'];  
                    $item = self::MIB($item);
                    $result[] = $item;
                }

            }
        }

        $columns[] = 'product_id';
        $columns[] = 'attribute_id';

        DB::insert('items', $columns, $result);
    }
 
}