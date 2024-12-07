<?php 

namespace app\core;

class Middleware {

    public static function auth () {
        if(!App::$app->user){
            echo '403 | Unuathorized';
            exit;
        }
    }

    public static function apiAuth () {
        if($_SERVER["HTTP_AUTHORIZATION"] !== "Bearer jshhcidav6s+7g84vs1z68d+e") {
            echo '403 | Unauthrized';
            exit;
        }
    }
    
} 