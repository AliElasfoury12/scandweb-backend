<?php 

namespace app\core\relations;

use app\core\App;
use app\core\database\DB;
use app\core\Model\MainModel;


class BelongsTo extends Relations {
    
    public static function run ($table1, $table2, $foreignKey = '', $primryKey = '') 
    {
        //table1 posts belongsTO table2 users
       $totalQuery = self::getQuery($table1);
       
        if(self::$relationData){
            $result1 = self::$relationData; 
        }else {
            $result1 = self::fetchBelongsToTable(
                $table1,
                $table1,
                $table2,
                $primryKey,
                $foreignKey,
                $totalQuery); //posts
        }

        $result2 = self::fetchBelongsToTable(
            $table2,
            $table1,
            $table2,
            $primryKey,
            $foreignKey); //users


        for ($i=0; $i < count($result1); $i++) { 
            $result1[$i][self::$relationName] = $result2[$i];
        }

        MainModel::$query = [];
        return $result1;
    }

    private static function fetchBelongsToTable ($table, $table1, $table2, $primryKey, $foreignKey, $query = '') 
    {
 
        $requestedCoulmns = self::$requestedCoulmns[self::$relationName];

        if ($table == $table2 && $requestedCoulmns) 
        {
            $requestedCoulmns = self::implodeColumns($table, $requestedCoulmns);
        }else{
            $requestedCoulmns = "$table.*";
        }

        $select = App::$app->model::$query['select'];        
        if ($table == $table1 && $select) 
        {
            $requestedCoulmns = self::implodeColumns($table, $select);
        }

        $sql = "SELECT $requestedCoulmns FROM 
        $table1 LEFT JOIN $table2 
        on $table2.$primryKey = $table1.$foreignKey $query"; //users.id = posts.user_id
        //echo $sql;
       
        return DB::fetchASSOC($sql);
    }
}