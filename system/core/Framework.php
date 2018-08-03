<?php

class Framework {
    private $elements;
    private $router;

    protected $registry;

    public function __construct() {
        $this->init();
//        $this->registry = new Registry();

        $this->router = new Router($_SERVER['REQUEST_URI']);
        /*$this->rewriteEngine();
        $this->autoload();*/
        echo $this->dispatch();
    }

    /*public  function run(){
        $this->init();
        $this->registry = new Registry();

        $this->rewriteEngine();
        $this->autoload();
        $this->dispatch();
    }*/

    private function init() {
        require_once "system/config/init_config.php";
    }

    private function dispatch() {
        $controller_name = $this->transformPath(CURR_CONTROLLER);
        $action_name = CURR_METHOD ;
        $controller = new $controller_name($this->registry);
        try {
            if(method_exists($controller, $action_name)){
                return $controller->$action_name(); // lead controller method
            } else {
                throw new Exception('Class method does not exists'); // throw exception
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n"; // echo the exception. TODO : update that to be logged and not displayed
            //$this->load('ControllerErrorNot_found');
        }
    }

    private function transformPath($path) {
        $elements = array_filter(explode('/', $path));
        if(!isset($elements[1])){
            $elements = array_filter(explode('\\', $path)); //Local Windows fix
        }
        $classname = trim(ucfirst(CONTROLLER), DS);
        foreach($elements as $element)
            $classname .= str_replace('_','', ucfirst($element));
        return $classname;
    }
}