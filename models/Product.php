<?php 

namespace app\models;

class Product extends Model {

    public static $fillable = [
     
    ];

    public function gallery () 
    {
        return $this->hasMany(Gallery::class);
    }

    public function attributes () {
        return $this->hasMany(Attribute::class);
    }

    public function items () 
    {
        return $this->hasMany(Item::class);
    }

    public function prices () {
        return $this->hasOne(Price::class);
    }

}