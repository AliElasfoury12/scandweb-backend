<?php 

namespace app\models;

class Order extends Model {

    public static $fillable = [
        'id',
        'product_id',
        'attributes',
        'quantity'
    ];

}