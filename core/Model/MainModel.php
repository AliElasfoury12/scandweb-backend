<?php 

namespace app\core\Model;

use app\core\database\DB;
use app\core\relations\Relations;

class MainModel extends Relations {
    use Create, InsertArr;
    public static array $query = ['where' => [], 'query' => [], 'select' => []];

    static public function addPropsToClass ($inputs)
    {
        $class = get_called_class();
        $model = new $class();
        foreach ($inputs as $prop => $value) {
           $model->{$prop} = $value; 
        }
        return $model;
    }

    static public function all ($columns = '*')
    {
        $class = get_called_class();
        $tableName = self::getTableName($class);
        if($columns != '*') {
            $columns = implode(', ', $columns);
        }
        $sql = "SELECT $columns FROM $tableName";
       // echo "$sql </br>";
        return  DB::fetchASSOC($sql);
    }

   
    static public function find ($value, $column = 'id') 
    {
        $className = get_called_class();
        $tableName = self::getTableName($className);
        $sql = "SELECT * FROM $tableName WHERE $column = '$value'";
        return  DB::fetch($sql, $className);
    }

    static public function first () 
    {
        self::$query['query'][] = "LIMIT 1";
        return self::get();
    }

    static public function get ()  
    {
        $class = get_called_class();
        $tableName = self::getTableName($class);
        $sql = self::sql();

        if(self::$query['select']) {
            $select = implode(', ', self::$query['select']);
        }else {
            $select = '*';
        }

        $sql = "SELECT $select FROM $tableName $sql";
        //echo $sql;
        self::$query = [];
        return DB::fetchASSOC($sql);
    }

    static public function getTableName ($class, $nameSpace = 'app\models') //app\models\User
    {
        $class = str_replace($nameSpace,"" , $class);// \User
        $class = stripslashes($class);// User
        $class = strtolower($class);// user 
        
        $sql = "show TABLES LIKE '$class'";
        $exsists = DB::fetchASSOC($sql);
        !$exsists ?  $table = $class.'s':  $table = $class;
    
        return  $table;
    }

    static public function limit ($limit) //app\models\User
    {
        self::$query['query'][] = "LIMIT $limit";
        return  new static;
    }

    static public function sql ()  
    {
        $where = self::$query['where'];
        if(!empty($where)) {
            $where = implode(' AND ', $where);
            $where = "WHERE $where";
        }else{
            $where = '';
        }

        $query = self::$query['query'];
        if(!empty($query)) {
            $query = implode('  ',$query);
        }else{
            $query = '';
        }

        return "$where $query";
    }

    static public function select ($columns)  
    {
        self::$query['select'] = $columns ;
        return  new static;
    }

    public static function where ($column , $state, $value) 
    {
        self::$query['where'][] = "$column $state '$value'"; 
       // var_dump(self::$query['where']);
        return  new static;
    }

    public static function minRepeat ($column) 
    {
        $class = get_called_class();
        $tableName = self::getTableName($class);

        $sql = "SELECT $column FROM $tableName
        GROUP BY $column HAVING COUNT(*) > 1
        ORDER BY COUNT(*) ASC LIMIT 1";

        $result = DB::fetchASSOC($sql);
        return $result[0][$column];
    }
}