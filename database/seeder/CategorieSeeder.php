<?php

namespace app\database\seeder;

class CategorieSeeder extends Seeder {

    public static function prepare ($categories) 
    {       
        $result = [];
        foreach ($categories as $categorie) {
            $values = array_values( $categorie);
            $result[] = self::MIB($values); 
        }

        return $result;      
    }
}