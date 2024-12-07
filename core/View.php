<?php

namespace app\core;

use app\core\Model\MainModel;
use app\core\request\Validator;

class View   {

    public static function prepare ($view) {
        $view = str_replace('.', '/', $view);
        $viewContent = file_get_contents(App::$ROOT_PATH."/views/$view.php");
        return self::blade($viewContent);
    }

    public  function layoutView ($view, $layout, $params = []) 
    {
        $title = $view;
        extract($params);

        $errors = Validator::$errorsMessages;
        $errors = MainModel::addPropsToClass($errors);

        $layoutContent = $this->prepare($layout);
        $viewContent = $this->prepare($view);
        
        $content = str_replace('@content', $viewContent, $layoutContent);

        file_put_contents(App::$ROOT_PATH.'/cache/main.php', $content);

        $content = eval("?>". $content);

        return $content;
        
    }

     public function view ($view, $params = []) {
        $title = $view;
        extract($params);

        $errors = Validator::$errorsMessages;
        $errors = MainModel::addPropsToClass($errors);

        $content = self::prepare($view);
        $content = eval("?>". $content);

        return $content;

    }
 
    static public function blade ($content) {
        $guest = App::isGuest() ? 1 : 0;
        $user = $guest ? 0 : 1;


        $content = preg_replace('/{{\s*(.*?)\s*}}/', '<?php echo $1 ;?>', $content);

        $content = preg_replace('/@if\s*\(\s*(.*?)\s*\)/', '<?php if($1): ?>', $content);
        $content = str_replace('@else', '<?php else: ?>', $content);
        $content = str_replace('@endif', '<?php endif; ?>', $content); 

        $content = preg_replace('/@foreach\s*\(\s*(.*?)\s*\)/', '<?php foreach($1): ?>', $content);
        $content = str_replace('@endforeach', '<?php endforeach ?>', $content);

        $content = preg_replace('/@include\s*(.*?)\s*/', '<?php include $1 ?>', $content);

        $content = str_replace('@guest', "<?php if($guest): ?>", $content);
        $content = str_replace('@endGuest', '<?php endif; ?>', $content);

        $content = str_replace('@auth', "<?php if($user): ?>", $content);
        $content = str_replace('@endAuth', '<?php endif; ?>', $content); 

        return $content;
    }

}