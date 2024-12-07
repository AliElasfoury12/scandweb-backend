<?php 

namespace app\models;

class User extends Model {

    public static $fillable = [
        'name',
        'email',
        'password'
    ];
}