<?php

namespace app\core;

use app\database\seeder\Seeder;

class Command  
{
    public function handleCommand ($argv) 
    {
        $app = App::$app;

        if($argv[0] != 'bmbo' || empty($argv[1])) {
            $this->notFound();
        }
    
        if($argv[1] == 'migrate'){
            $app->migrations->applyMigrations();
            exit;
        }
        
        if(
            $argv[1] == 'migration' &&
            str_contains($argv[2], 'create') &&
            str_contains($argv[2], 'table')
        ){
            $app->migrations->createTable($argv[2]);
        }
        
        if($argv[1] == 'seed'){
            $this->seedCommand();
        }
        
        if($argv[1] == 'model'){
           $this->createModel($argv[2]);
        }
        
        if($argv[1] == 'controller'){
           $app->controller->createController($argv[2]);
        }
        
       $this->notFound();
    }

    public function notFound () {
        echo "Command Not Found \n" ;
        exit;
    }

    public function seedCommand () {
        echo "Seeding.....................\n";
        Seeder::run();
        echo "Seeding Finshed Successfully\n";
        exit;
    }

    public function createModel ($fileName) {
        $modelFile = file_get_contents(__DIR__.'/layouts/createModel.php');
        $modelFile = preg_replace('/class\s*(.*?)\s*extends/', "class $fileName extends",  $modelFile);
        $exists = file_exists(__DIR__."/../models/$fileName.php");
        if($exists){
            echo "[ models/$fileName ] - file already exsists \n";
            exit;
        }
        file_put_contents(__DIR__."/../models/$fileName.php", $modelFile);
        echo "[ models/$fileName ] - Created Successfully \n";
        exit;
    }
}