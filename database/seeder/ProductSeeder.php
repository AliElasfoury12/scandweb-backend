<?php

namespace app\database\seeder;

class ProductSeeder extends Seeder {

    public static function prepare ($product, $columns) 
    {    
        $values = [];   
        foreach ($columns as $column)
        {
            if ( $column == 'inStock') {
                $product[$column] == true ? $product[$column] = 1 : $product[$column] = 0;
            }

            $values[] = $product[$column];
        }

        return self::MIB($values); 
    }

}