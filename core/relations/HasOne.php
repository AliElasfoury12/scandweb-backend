<?php 

namespace app\core\relations;

use app\core\App;
use app\core\database\DB;
use app\core\Model\MainModel;
class HasOne extends Relations {
    public static function run ($table1, $table2, $foreignKey = '', $primryKey = '') 
    {
        //table1 products hasOne table2 prices
       
        $totalQuery = self::getQuery($table1);

        if(self::$relationData){
            $result1 = self::$relationData; 
        }else {
            $result1 = self::fetchHasOneTable(
                $table1,
                $table1,
                $table2,
                $primryKey,
                $foreignKey,
                $totalQuery); //products
        }

        $result2 = self::fetchHasOneTable(
            $table2,
            $table1,
            $table2,
            $primryKey,
            $foreignKey); //prices


        for ($i=0; $i < count($result1); $i++) { 
            $result1[$i][self::$relationName] = $result2[$i];
        }
        MainModel::$query = [];
        return $result1;
    }

    private static function fetchHasOneTable ($table, $table1, $table2, $primryKey, $foreignKey, $query = '') 
    {
        $requestedCoulmns = self::$requestedCoulmns[self::$relationName];

        if ($table == $table2 && $requestedCoulmns) //prices 
        {
            $requestedCoulmns = self::implodeColumns($table, $requestedCoulmns);
        }else{
            $requestedCoulmns = "$table.*";
        }

        $select = App::$app->model::$query['select'];        
        if ($table == $table1 && $select) // products
        {
            $requestedCoulmns = self::implodeColumns($table, $select);
        }

        $sql = "SELECT $requestedCoulmns FROM 
        $table1 LEFT JOIN $table2 
        on $table1.$primryKey = $table2.$foreignKey $query"; //products.id = prices.product.id
        //echo " $sql </br>";
        return DB::fetchASSOC($sql);
    }
}