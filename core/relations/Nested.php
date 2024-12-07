<?php 

namespace app\core\relations;

use app\core\database\DB;
class Nested extends Relations {
   
    public static function run ($class, $relation) 
    {
        $postion = strpos( $relation, '.');
        $relation1 = substr($relation, 0, $postion); //posts

        $exists = false;
        foreach (self::$relationData as $result) {
            if($result[$relation1]){
                $exists = true;
                break;
            }
        }
 
        if(!$exists) {
            self::$relationName = $relation1; //posts
            self::handleRelation($class);
        }
       
        $relation2 = str_replace($relation1.'.', '', $relation); //comments
        $primaryKey = self::getPK($relation1);//posts.id
        $index = rtrim($relation1, 's');//product
        $foreignKey = self::getFK($relation2, $index);// post_id

        $class = self::getClassName($relation1);
        $class = new $class();// new Post()

        $nestedRelation = call_user_func([$class, $relation2]);
        $columns = self::$requestedCoulmns["$relation1.$relation2"] ?? '*';
        if($columns != '*') {
            $columns = implode(', ', $columns);
        }

        //App::dump(self::$relationData);

        if($nestedRelation[0] == 'HASMANY') {
            foreach (self::$relationData as &$items) // $users as user
            {
                $items = &$items[$relation1]; // user['posts]
                if($items[$primaryKey]){
                    $items[$relation2] = self::combine(
                        $items,
                   $primaryKey,
                      $columns,
                    $relation2, 
                   $foreignKey); // $post[comments] = nested
                }else{
                    foreach ($items as &$item) //posts as post
                    {
                        $item[$relation2] = self::combine(
                            $item,
                       $primaryKey,
                          $columns,
                        $relation2, 
                       $foreignKey); // $post[comments] = nested
                    }
                }

            }
        }
    }

    private static function combine ($items, $primaryKey, $columns, $relation2, $foreignKey) 
    {
        $id = $items[$primaryKey];//post['id']
        $sql = "SELECT $columns FROM $relation2 WHERE $foreignKey = '$id'";
       // echo "$sql </br>";
        return DB::fetchASSOC($sql); //comments
    }

    private static function getClassName ($relation) 
    {
        $class = ucfirst($relation);//Posts
        $class = trim($class, 's');//Post
        $class = "app\models\/$class";
        return str_replace('/', '', $class);//app\models\Post
    }
}