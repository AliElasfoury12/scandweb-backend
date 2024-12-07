<?php

namespace app\database\seeder;

class GallerySeeder extends Seeder {
 
    public  static function prepare ($product, $foreignId) 
    {
        $images = $product['gallery'];
        $result = [];

        foreach ($images as $image) {
            $values = [$foreignId, $image];
            $result[] = self::MIB($values); 
        }

        return implode(', ', $result);
    }
}