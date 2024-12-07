<?php

namespace app\core\request;

class Request  {
    public function __construct () {
        
    }

    public function getPath () {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        if($path == '/') {
            return $path = '/';
        }
        $position = strpos($path, '?');

        if($position) {
            $path = substr($path, 0, $position);
        }

        if(substr($path,-1) === '/'){
            $path = substr($path,0,-1);
        }

        //path
        return $path;
    }

    public function method () {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet () {
        return $this->method() === 'get';
    }

    public function isPost () {
        return $this->method() === 'post';
    }

    public function getBody () {
        $body = [];

        if($this->method() === 'get'){
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->method() === 'post'){
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function validate (array $rules) {
       $body = $this->getBody();

        $errors =  Validator::check($body, $rules);

        if($errors) {
           return $errors;
        }else {
            return  (object) $body;
            //Model::addPropsToClass($body);
        }
    }
}