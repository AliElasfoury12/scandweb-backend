<?php 
namespace app\core\Model;

use app\core\database\DB;
trait InsertArr
{
    static public function insertArr ($inputs) {
        if (is_string($inputs)) {
            return $inputs;
        }

        $className = get_called_class();
        $fillable = $className::$fillable;
        $tableName = self::getTableName($className);
        $result = [];

        $columns = array_keys($inputs[0]);

        foreach ($inputs as $input) {
            foreach ($input as $column => $value) {
                if(in_array($column, $fillable)) {
                    if( str_contains(strtolower($column), 'password')){
                       $value = password_hash($value, PASSWORD_DEFAULT);
                    }
                    $values[]  =  "'$value'";
                }
            }

            if(empty($value)){continue;}

            $values = implode(',', $values);
            $values = "($values)";
            $result[] = $values;
            $values = []; 
        }

        $columns = implode(', ',$columns);
        $result = implode(', ',$result);

        $sql = "INSERT INTO $tableName ( $columns ) VALUES  $result";
        //echo $sql;
        DB::exec($sql);

        return $className::addPropsToClass($inputs);
    }
}
