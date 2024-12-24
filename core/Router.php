<?php

namespace app\core;

class Router  {
    public View $view;
    protected array  $routes = [];
    public array  $middlewares = []; 
    protected  $lastPath = '';
    protected  $lastMethod = '';

    public  function get($path, $callback) {
        
        $this->lastPath = $path;
        $this->lastMethod = 'get';
        $this->routes['get'][$path] = $callback;
        return  $this ; 
    }

    public  function post($path, $callback)
    {
        $this->lastPath = $path;
        $this->lastMethod = 'post';
        $this->routes['post'][$path] = $callback;
        return  $this; 
    }

    public function resolve () 
    {
        $request = App::$app->request;

        $path = $request->getPath();
        $method = $request->method();
        $callback = $this->routes[$method][$path];

        if(!$callback){
            $reponse = App::$app->view->view('404_page');
            return Response::response($reponse, 404);
        }

        if(is_string($callback)){
            return $this->view->view($callback);
        }

        if (is_array($callback)){
            $callbackFun =  new $callback[0];
            $callback = [$callbackFun, $callback[1]];
        }

        if($this->middlewares){
            $middlewares = $this->middlewares[$method][$path];
            if($middlewares) {
                foreach ($middlewares as $middleware) {
                call_user_func(['app\core\Middleware', $middleware]);
                }
            }
        }
       
        $arges = [];
        if(array_key_exists('QUERY_STRING', $_SERVER)) {
            parse_str($_SERVER['QUERY_STRING'], $queryString);
            $queryString = array_values($queryString);
            $arges = [ $request];
            foreach ($queryString as $value) {
                $arges[] = $value;
            }
        }

        return call_user_func_array ($callback, $arges);
    }

    public static function redirect (string $url)
    {
        header("Location: $url");
    }

 
    public  function middelware (array $middlewares) 
    {
        foreach ($middlewares as $middleware) {
            if ($this->middlewares[$this->lastMethod][$this->lastPath]) {
                $this->middlewares[$this->lastMethod][$this->lastPath][] = $middleware;
            }else {
                $this->middlewares[$this->lastMethod][$this->lastPath] = [$middleware];
            }
        } 
    }

}