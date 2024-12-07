<?php 
namespace app\core\Model;

use app\core\database\DB;
trait Create
{
    static public function create ($inputs) {
        if (is_string($inputs)) {
            return $inputs;
        }

        $className = get_called_class();
        $fillable = $className::$fillable;
        $tableName = self::getTableName($className);
        $values = [];

        $columns = array_keys($inputs);

        foreach ($inputs as $column => $value) {
            if(in_array($column, $fillable)) {
                if( str_contains(strtolower($column), 'password')){
                   $value = password_hash($value, PASSWORD_DEFAULT);
                }
            $values[]  =  "'$value'";
            }
        }

        $columns = implode(', ',$columns);
        $values = implode(', ',$values);

        $sql = "INSERT INTO $tableName ( $columns ) VALUES ( $values )";
        echo $sql;
        DB::exec($sql);

        return MainModel::addPropsToClass($inputs);
    }
}
