<?php

namespace app\core;

class MainController 
{
    public function layoutView ($view, $layout, $params = []) {
        return App::$app->view->layoutView($view, $layout, $params);
    }

    public function view ($view, $params = []) {
        return  App::$app->view->view($view, $params);
    }

    public function createController ($fileName) 
    {
        $controllerFile = file_get_contents(__DIR__.'/layouts/controller.php');
        $controllerFile = preg_replace('/class\s*(.*?)\s*extends/', "class $fileName extends",   $controllerFile);
        $exists = file_exists(__DIR__."/../controllers/$fileName.php");
        if($exists){
            echo "[ controllers/$fileName ] - file already exsists \n";
            exit;
        }
        file_put_contents(__DIR__."/../controllers/$fileName.php",  $controllerFile);
        echo "[ controllers/$fileName ] - Created Successfully \n";
        exit;
    }
   
}