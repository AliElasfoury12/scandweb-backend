<?php 

namespace app\core\request;

class Validator 
{
    public static $errors = [];
    public static $errorsMessages = [];


    public static function check (array $body, array $rules) {

        foreach ($body as $field => $value) {
            
            foreach ($rules[$field] as $rule) {

                self::required($field,$value, $rule);

                self::min($field,$value, $rule);

                self::max($field,$value, $rule);

                self::email($field,$value, $rule);

                self::match($field,$value, $rule, $body);
 
                self::password($field,$value, $rule);
            }
        } 

        if(self::$errors){
             self::getErrorMessages();
             return self::$errorsMessages;
        }
    }

    public static function getErrorMessages () {
        foreach (self::$errors as $field => $message) {
            self::$errorsMessages[$field] = self::$errors[$field][0];
        }
    }

    public static function addErrorMessage ($field, $message) {
        if(self::$errors[$field]){
            self::$errors[$field][] = $message;
        }
        else{
            self::$errors[$field] = [$message];
        }
    }

    public static function required ($field, $value, $rule) {
        if($value == '' && $rule == 'required'){

            $message = "$field is required";
           self::addErrorMessage($field,$message);
        }
    }

    public static function email ($field, $value, $rule) {
        if($rule == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)){

            $message = "$field is Not a Valid Email";
            self::addErrorMessage($field,$message);
        }
    }

    public static function password ($field, $value, $rule) {
        if($rule == 'password'){

            $message = "$field is Not a Valid password";
            self::addErrorMessage($field,$message);
        }
    }

    public static function match ($field, $value, $rule, $body) {
        if(str_contains($rule,'match')){
            $match = str_replace('match:','',$rule,);
            if( $body[$match] != $value){

                $message = "$field don't Match $match";
                self::addErrorMessage($field,$message);
            }
        }
    }

    public static function min ($field, $value, $rule) {
        if(str_contains($rule,'min')){
            $min = str_replace('min:','',$rule,);
            $fieldLength = strlen($value) ;
            if($fieldLength < $min){

                $message = "$field must be at least $min characters long";
                self::addErrorMessage($field,$message);
            }
        }
    }


    public static function max ($field, $value, $rule) {
        if(str_contains($rule,'max')){
            $max = str_replace('max:','',$rule,);
            $fieldLength = strlen($value) ;
            if($fieldLength > $max){
                
                $message = "$field must be less than $max characters long";
                self::addErrorMessage($field,$message);
            }
        }
    }
}