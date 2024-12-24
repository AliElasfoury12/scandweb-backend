<?php

namespace app\controllers;

use app\models\Gallery;
use app\models\Item;
use app\models\Product;

class ProductsController extends Controller {
    public static function handle($resolveInfo) 
    {
        $fields = $resolveInfo->getFieldSelection(2);
        $gallery = $fields['gallery'];
        $prices = $fields['prices'];
        $attributes = $fields['attributes'];
        $attributes ? $items = $attributes['items'] : $items = [];
        $with = [];

        if($gallery) {
            unset($fields['gallery']);
        }

        if($prices) {
            $pricesFields = array_keys($prices);
            $pricesFields = implode(',', $pricesFields);
            unset($fields['prices']);
            $with[] = "prices:$pricesFields";
        }

        if($attributes){
            if($items){
                unset($fields['attributes']['items']);
            }

            $attributesFields = array_keys($fields['attributes']);
            $attributesFields = implode(',', $attributesFields);
            unset($fields['attributes']);
            $with[] = "attributes:$attributesFields";
        }


        $fields = array_keys($fields);
        if($prices){
            $products = Product::select($fields)->with($with);
        }else {
            $products = Product::all($fields);
        }

        if($gallery) {
            foreach ($products as &$product) {
                $img = Gallery::where('product_id', '=', $product['id'])->first();
                $product['gallery'] = $img[0]['img'];
            }
        }

        if($items) {
            $itemsFields = array_keys($items);
            foreach ($products as &$product) {
                $attributes = &$product['attributes'];
                foreach ($attributes as &$attribute) {
                    $item = Item::select($itemsFields)
                    ->where('attribute_id', '=', $attribute['id'])
                    ->where('product_id', '=', $product['id'])
                    ->get();
                    $attribute['items'] = $item;
                }
            }
        }
        return $products;
    }
}