<?php

namespace app\database\seeder;

use app\core\DB;

class PriceSeeder extends Seeder {
  
    public static function prepare ($product) 
    {
        $prices = $product['prices'][0];
        $prices = array_values($prices);
        $prices[1] = json_encode($prices[1]);
        $values = $prices;
        $values[] = $product['id'];
        $result = self::MIB($values); 
        
        return $result;
    }
 
}