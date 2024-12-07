<?php

namespace app\controllers;

use app\models\Item;
use app\models\Product;

class ProductController extends Controller {
    public static function handle ($resolveInfo, $id) 
    {
        $fields = $resolveInfo->getFieldSelection(2);
        $gallery = $fields['gallery'];
        $prices = $fields['prices'];
        $attributes = $fields['attributes'];
        $attributes ? $items = $attributes['items'] : $items = [];
        $with = [];

        if($gallery) {
            unset($fields['gallery']);
            $with[] = "gallery:img";

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
        if($gallery || $prices || $attributes) {
            $product = Product::select($fields)->where('id', '=', $id)->with($with);
        }else {
            $product = Product::select($fields)->where('id', '=', $id)->get();
        }

        if($items) {
            $itemsFields = array_keys($items);
            $attributes = &$product[0]['attributes'];
            foreach ($attributes as &$attribute) {
               $item = Item::select($itemsFields)
               ->where('attribute_id', '=', $attribute['id'])
                ->where('product_id', '=', $product[0]['id'])
                ->get();
                $attribute['items'] = $item;
            }
        }
        return $product;
    }
}