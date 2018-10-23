<?php

class Framework {
    private $elements;
    private $router;

//    protected $registry;

    public function __construct() {
        $this->init();
//        $this->registry = new Registry();

        $this->router = new Router($_SERVER['REQUEST_URI']);
        echo $this->router->dispatch();
    }

    private function init() {
        require_once "system/config/init_config.php";
    }
}