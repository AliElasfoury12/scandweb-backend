<?php

namespace app\core;

use app\core\database\DB;
use app\core\database\migrations\Migrations;
use app\core\Model\MainModel;
use app\core\request\Request;
use app\models\User;

class App  {

    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    protected array $eventListeners = [];
    public static string $ROOT_PATH;    
    public Router $router;
    public Request $request;
    public View $view;
    public Session $session;
    public DB $db;
    public ?User $user = null;
    public MainModel $model;
    public Migrations $migrations;
    public Command $command;
    public MainController $controller;
    public static App $app;

    public function __construct ($rootPath) {
        self::$ROOT_PATH = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->view = new View();
        $this->session = new Session();
        $this->db = new DB();
        $this->user = new User();
        $this->model = new MainModel();
        $this->migrations  = new Migrations();
        $this->command = new Command();
        $this->controller = new MainController();
        $this->router = new Router();

        if($this->session->get('user')){
            $this->user = $this->session->get('user');
        }
    }

    public function run() {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        echo $this->router->resolve();
    }

    public static  function dd (array $vars) {
       self::dump($vars);
        exit;
    }

    public static  function dump (array $vars) {
        foreach ($vars as $var) {
            echo "<pre>";
            var_dump($var);
            echo "</pre> \n";
        }
    }

    public function login (User $user) {
       $this->user = $user;
       $this->session->set('user', $user);
    }

    public function logout ( ) {
        $this->user = null ;
        $this->session->remove('user');
    }

    public static function isGuest ( ) 
    {
       return !self::$app->user; 
    }

    public function on($eventName, $callback) {
      
        $this->eventListeners[$eventName][] = $callback;
    }

    public function triggerEvent($eventName) {
      
        $callbacks = $this->eventListeners[$eventName] ?? [] ;

        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    
}