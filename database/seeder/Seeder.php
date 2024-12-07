<?php

namespace app\database\seeder;

use app\core\App;
use app\core\database\DB;

class Seeder {

    public static function run () 
    {
        $data = file_get_contents(App::$ROOT_PATH.'/data/data.json');
        $data = json_decode($data,true);
        $data = $data['data'];

        $categories = $data['categories'];
        $categories = array_values($categories);
        $categoriesColumns = array_keys($categories[0]);
        $categoriesResult = CategorieSeeder::prepare($categories);

        $products = $data['products'];
        $productsColumns = self::getColumns($products[0]);
        $productsResult = [];

        $galleryResult = [];

        $attributesItem = $products[0]['attributes'][0];
        $attributesColumns = self::getColumns( $attributesItem);
        $attributesResult = [];

        $pricesItem = $products[0]['prices'][0];
        $pricesColumns = array_keys($pricesItem);
        $pricesResult = [];

       foreach ($products as $product) {
            $foreignId = $product['id'];

            $productsResult[] = ProductSeeder::prepare($product, $productsColumns);
            $galleryResult[] = GallerySeeder::prepare($product, $foreignId);
            $attributes = AttributesSeeder::prepare($product, $attributesColumns, $foreignId ); 
            if($attributes) {
                foreach ($attributes as $attribute) {
                    $attributesResult[]= $attribute;
                }
            }
            $pricesResult[] = PriceSeeder::prepare($product);
        }

        $galleryResult = implode(', ', $galleryResult);
        $attributesColumns[] = 'product_id';
        $pricesColumns[] = 'product_id'; 

        $finalResult = [
            ['categories', $categoriesColumns, $categoriesResult],
            ['products', $productsColumns, $productsResult],
            ['gallery', 'product_id, img',$galleryResult],
            ['attributes', $attributesColumns, $attributesResult],
            ['prices', $pricesColumns, $pricesResult],
        ] ;

        foreach ($finalResult as $result)
        {
            DB::insert($result[0], $result[1], $result[2]);
        }

        ItemsSeeder::seed($products);
    }

    public static function MIB ($values)//Mapp-implode-brackets 
    {
        $values = array_map(fn($value) => "'$value'",$values);
        $values = implode(', ',  $values);
        return "( $values )"; 
    }

    public static function getColumns ($item)//Mapp-implode-brackets 
    {
        $columns = array_keys($item);
        $result = [];

        foreach ($columns as $column)
        {
            if(is_string($item[$column]))
            {
                $result [] = $column;
            }

            if(is_bool($item[$column]))
            {
                $result [] = $column;
            }
        }  

        return $result;
    }
}