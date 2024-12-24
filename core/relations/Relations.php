<?php 

namespace app\core\relations;

use app\core\App;
use app\core\database\DB;
use app\core\Model\MainModel;

class Relations {
    public static $relationName ;
    public static $relationData = [];
    public static $requestedCoulmns = [];

    public static function with(array $relations){
        $class = get_called_class();
        $class = new $class();
        
        foreach ($relations as $relation) { 
            if(str_contains($relation, ':')) //posts:id,post
            {
                self::handleRequestedCoulms($relation);
                $relation = self::$relationName;
            }

            if(str_contains($relation, '.')) //posts.comments
            {
                Nested::run($class, $relation);
                continue;
            }

            self::$relationName = $relation;
            self::handleRelation($class);
        }

        return self::$relationData;
    }

    public static function handleRelation ($class) 
    {
        $relation = call_user_func([$class, self::$relationName]);// users.posts
        $r = $relation;

        switch ($r[0]) {
            case 'HASMANY':
                self::$relationData = HasMany::run($r[1], $r[2], $r[3], $r[4]);
            break;

            case 'BELONGSTO' :
                self::$relationData = BelongsTo::run($r[1], $r[2], $r[3], $r[4]);
            break;

            case 'HASONE':
                self::$relationData = HasOne::run($r[1], $r[2], $r[3], $r[4]);
            break;
        }
    }

    public static function handleRequestedCoulms ($relation) 
    {
        $postion = strpos( $relation, ':');
        self::$relationName = substr($relation, 0, $postion);//posts
        $coulmns = str_replace(self::$relationName.':', '', $relation);//id,post
        self::$requestedCoulmns[self::$relationName] = explode(',',$coulmns); //reqcol[posts]
    }

    public static function implodeColumns ($table, $coulmns) 
    {
        $coulmns = array_map(fn($column) => "$table.$column" ,$coulmns);
        return implode(' , ', $coulmns);
    }

    public static function handleSelect () 
    {
        $model = App::$app->model;
        $select = $model::$query['select'];
        if($select){
            $select = implode(', ', $select);
        }else {
            $select = '*';
        }

        return $select;
    }
    
    public static function getPK ($table) 
    {
        $sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
        $result = DB::fetchASSOC($sql);
        return $result[0]["Column_name"];
    }

    public static function getFK ($table, $keyPart) 
    {
        $sql = "SHOW KEYS FROM $table WHERE Key_name Like '%$keyPart%'";
        $result = DB::fetchASSOC($sql);
        return $result[0]["Column_name"];
    }

    public static function getQuery ($table1) 
    {
        $query = MainModel::$query;
        $wheres = '';
        $extraQuery = '';

        if(array_key_exists('where', $query)) {
            $wheres = $query['where'];
            if(!empty($wheres)) {
                if(count($wheres) > 1) {
                    foreach ($wheres as &$where) {
                        $where = "$table1.$where";
                    }
                    $wheres = implode(' AND ', $wheres);
                }
                $wheres = "WHERE $wheres";
            }else {
                $wheres = '';
            }
        }
       
        if(array_key_exists('query', $query)) {
            $extraQuery = $query['query'];
            if(!empty($extraQuery)) {
                $extraQuery = implode(' ', $extraQuery);
            }else {
                $extraQuery = '';
            }
        }

       return  "$wheres $extraQuery";
    }

    public function hasOne ($class2, $foreignKey = '', $primaryKey = '') 
    {
       return $this->tablesAndKeys($class2, $foreignKey, $primaryKey,'HASONE');
    }

    public function belongsTo ($class2, $foreignKey = '', $primaryKey = '') 
    {
        //table1 posts belongsTO table2 users

        $class = get_called_class();
        $table1 = MainModel::getTableName($class );//posts
        $table2 = MainModel::getTableName($class2 );//users

        !$primaryKey ? $primaryKey = $this->getPK($table2) : '';
        !$foreignKey ? 
        $foreignKey = $this->getFK($table1, substr($table2, 0, -1)) 
        : '';

        return ['BELONGSTO', $table1, $table2, $foreignKey, $primaryKey];
    }

    public function hasMany ($class2, $foreignKey = '', $primaryKey = '') 
    {
        return $this->tablesAndKeys($class2, $foreignKey, $primaryKey,'HASMANY');
    }

    public function tablesAndKeys ($class2, $primaryKey, $foreignKey, $relation) {
        $class = get_called_class();
        $table1 = MainModel::getTableName($class );//posts
        $table2 = MainModel::getTableName($class2 );//users

        !$primaryKey ? $primaryKey = $this->getPK($table1) : '';
        !$foreignKey ? 
        $foreignKey = $this->getFK($table2, substr($table2, 0, -1)) 
        : '';

        return [$relation, $table1, $table2, $foreignKey, $primaryKey];
    }

}