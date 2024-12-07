<?php

namespace app\database\seeder;

use app\core\App;

class AttributesSeeder extends Seeder {
 
    public static function prepare ($product, $columns, $foreignId) 
    {
        $result = [];
        $attributes = $product['attributes'];
        foreach ($attributes as $attribute) {
            foreach ($columns as $column) {
                $values[] = $attribute[$column];
            }
            $values[] = $product['id'];
            $result[] = self::MIB($values);
            $values= [];
        }
        return $result;
    }

}