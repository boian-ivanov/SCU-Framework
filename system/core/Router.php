<?php

class Router {
    private $uri;
    private $elements;

    public function __construct($uri) {
        $this->uri = $uri;

        $this->rewriteEngine();
        $this->autoload();
    }

    private function rewriteEngine() {
        $path = trim(strtok($this->uri, '?'), '/');

        $this->elements = $elements = array_filter(explode('/', $path));

        if(!isset($elements[1])){
            $elements = array_filter(explode('\\', $path)); // Local Windows fix
        }

        if(!isset($elements[0])){
            $elements[0] = 'common'; // Fix for undefined offset 0
        }

        $elements = $this->defineCurrDir();

        switch (count($elements)) {
            case 0:
                $path = 'common' . DS . 'index';
                break;
            case 1:
                $path = ($elements[0] ? $elements[0] : 'common') . DS . 'index';
                break;
            case 2:
                $path = $elements[0] . DS . ($elements[1] ? $elements[1] : 'index');
                break;
            default:
                $path = '';
                for($i=0; $i<count($elements)-1; $i++) {
                    $path .= $elements[$i] . DS;
                }
                $path = rtrim($path, DS);
                $method = end($elements);
                break;
        }

        if (!is_file(APP_PATH . CURR_DIR . CONTROLLER . strtolower($path) . ".php")) {
            if(($slug = $this->checkSlugDb()) === null) { // 404
                define("CURR_CONTROLLER", 'error' . DS . 'not_found');
                define("CURR_METHOD", 'index');
            } else {
                define("CURR_CONTROLLER", strtolower($slug['redirect']));
                define("CURR_METHOD", $slug['method'] != null ? strtolower($slug['method']) : 'index');
            }
        } else {
            define("CURR_CONTROLLER", strtolower($path));
            define("CURR_METHOD", isset($method) ? $method : 'index');
        }
    }

    private function autoload() {
        spl_autoload_register(array(__CLASS__,'load'));
    }

    private function load($classname) {
        $file = APP_PATH . CURR_DIR . CONTROLLER . CURR_CONTROLLER . ".php";
        try {
            if(!is_file($file)) {
                throw new Exception ($classname . ' does not exist');
            }
            else
                require_once($file);
        } catch(Exception $e) { // TODO : create an exception handler
            echo "Message : " . $e->getMessage();
            echo "</br>";
            echo "Code : " . $e->getCode();
        }
    }

    private function checkSlugDb() {
        $path = trim(strtok($this->uri, '?'), '/');
        $db = new Db('');

        $url_request = $db->select(DB_PREFIX . 'url_redirect')->where(["url_slug = '" . $db->escape($path) . "'"])->exec(0);

        if($url_request['url_slug'] === $path)
            return $url_request;
        else
            return null;
    }

    private function defineCurrDir() {
        $dir = isset($this->elements[0]) && $this->elements[0] === ADMIN_LINK ? ADMIN_PATH . DS : HOME_PATH . DS;
        define("CURR_DIR", $dir);
        if(isset($this->elements[0]) && $this->elements[0] === ADMIN_LINK) array_splice($this->elements, 0, 1);
        return $this->elements;
    }
}