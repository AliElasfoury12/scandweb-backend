<?php 

namespace app\models;

class Attribute extends Model {

    public static $fillable = [
     
    ];

    public function items () {
        return $this->hasMany(Item::class);
    }

}