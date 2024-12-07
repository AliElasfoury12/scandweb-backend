<?php 

namespace app\core\relations;

use app\core\database\DB;
use app\core\Model\MainModel;
class HasMany extends Relations {
    public static function run ($table1, $table2, $foreignKey = '', $primaryKey = '') :array 
    {
        //table1 users hasMany table2 posts
        $query = MainModel::sql();
       
        if(self::$relationData) {
            $result1 = self::$relationData;
        }else {
            $select = self::handleSelect();
            $sql = "SELECT $select FROM  $table1 $query";
            // echo $sql;
            $result1 = DB::fetchASSOC($sql);
        }

       $requestedCoulmns = self::$requestedCoulmns[self::$relationName];
       if ($requestedCoulmns) {
            $requestedCoulmns = self::implodeColumns($table2, $requestedCoulmns);
       }else {
           $requestedCoulmns = "*";
       }

        foreach ($result1 as &$result) { 
            $result1Id = $result[$primaryKey];
            $sql = "SELECT $requestedCoulmns 
            FROM $table2 
            WHERE $table2.$foreignKey = '$result1Id'";
            //echo "$sql </br>";
            $result2 = DB::fetchASSOC($sql);
            $result[self::$relationName] = $result2 ?? [];
        }

        MainModel::$query = [];
        return $result1;
    }
}