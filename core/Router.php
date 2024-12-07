<?php

namespace app\core;

class Router  {
    public View $view;
    protected array  $routes = [];
    public array  $middlewares = []; 
    protected  $lastPath = '';
    protected  $lastMethod = '';

    public static function get($path, $callback) {
        $router =  App::$app->router;
        $router->lastPath = $path;
        $router->lastMethod = 'get';
        $router->routes['get'][$path] = $callback;
        return  $router ; 
    }

    public static function post($path, $callback)
    {
        $router = App::$app->router;
        $router->lastPath = $path;
        $router->lastMethod = 'post';
        $router->routes['post'][$path] = $callback;
        return $router; 
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

        $middlewares = $this->middlewares[$method][$path];
        if($middlewares) {
            foreach ($middlewares as $middleware) {
               call_user_func(['app\core\Middleware', $middleware]);
            }
        }
       
        parse_str($_SERVER['QUERY_STRING'], $queryString);
        $queryString = array_values($queryString);
        $arges = [ $request];
        foreach ($queryString as $value) {
            $arges[] = $value;
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