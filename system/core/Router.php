<?php

class Router {
    private $uri;
    private $elements;
    private $arguments;

    private $parent_dir = HOME_PATH . DS;

    // define default controller path & method
    public $controller_path;
    public $controller;
    public $method;

    public function __construct($uri) {
        $this->uri = $uri;

        $this->rewriteEngine();
        $this->autoload();
    }

    private function setDefaults() {
        $this->controller_path = array('common');
        $this->controller = 'index';
        $this->method = 'index';
    }

    private function rewriteEngine() {
        $path = trim(strtok($this->uri, '?'), '/');

        $this->elements = array_filter(explode('/', $path));

        $this->defineParentDir();

        $this->build_path();
    }

    private function build_path () {
        $this->setDefaults();
        switch (count($this->elements)) {
            case 0:
                break;
            case 1:
                $this->controller = $this->elements[0];
                break;
            case 2:
                $this->controller = $this->elements[0];
                $this->method = $this->elements[1];
                break;
            default:
                $this->method = implode(array_slice($this->elements, -1, 1));
                $this->controller = implode(array_slice($this->elements, -2, 1));
                $this->controller_path = array_slice($this->elements, 0, -2);
                break;
        }
    }

    private function autoload() {
        spl_autoload_register(array(__CLASS__,'load'));
    }

    private function load($classname) { // TODO: rebuilt that a bit
        $file = APP_PATH . $this->parent_dir . CONTROLLER . implode(DS, $this->controller_path) . DS . $this->controller . ".php";
        try {
            if(!is_file($file)) {
//                echo $classname . ' does not exist';
                throw new Exception ($classname . ' does not exist');
            } else {
                require_once($file);
            }
        } catch(Exception $e) {
            // TODO : Really in need to log some of those, maybe not here, because this can trigger in cases that we have arguments, but we need to log exceptions !
            /*echo "Message : " . $e->getMessage();
            echo "</br>";
            echo "Code : " . $e->getCode();*/
        }
    }

    private function checkSlugDb() { // TODO: implement back that functionality
        $path = trim(strtok($this->uri, '?'), '/');
        $db = new Db('');

        $url_request = $db
            ->select(DB_PREFIX . 'url_redirect')
            ->where(["url_slug = '" . $db->escape($path) . "'"])
            ->exec(0);

        if($url_request['url_slug'] === $path)
            return $url_request;
        else
            return null;
    }

    private function defineParentDir() {
        if(isset($this->elements[0]) && $this->elements[0] === ADMIN_LINK) {
            $this->parent_dir = ADMIN_PATH . DS;
            // very important to remove the "admin" part from the "elements" array to no interfere with routing logic
            array_splice($this->elements, 0, 1);
        }
        define("CURR_DIR", $this->parent_dir); // left for compatibility reasons
    }

    public function dispatch() {
        $controller_name = $this->getClassName();
        $action_name = $this->method;
        try { // TODO : try and refactor current code to not be that messy (maybe something close to commented part at the bottom)
            if(class_exists($controller_name)) {
                $controller = new $controller_name(); // main controller object // new $controller_name($this->registry);
                if (method_exists($controller, $action_name)) {
                    return $controller->$action_name(); // lead controller method
                } else {
                    throw new Exception('Class method does not exists'); // throw exception
                }
            } else {
                $this->arguments = implode(array_splice($this->elements, -1, 1));
                $this->build_path();
                $controller_name = $this->getClassName();
                $action_name = $this->method;
                $controller = new $controller_name();
                if (method_exists($controller, '__rewrite')) {
                    if($this->rewrite($controller->__rewrite())) {
                        return $controller->$action_name();
                    } else {
                        throw new Exception('Class method does not exists'); // throw exception
                    }
                } else {
                    throw new Exception('Class method does not exists'); // throw exception
                }
            }

            /*if(!class_exists($controller_name)) {
                $this->arguments = implode(array_splice($this->elements, -1, 1));
                $this->build_path();
                $controller_name = $this->getClassName();
                $action_name = $this->method;
            }
            $controller = new $controller_name(); // main controller object // new $controller_name($this->registry);
            if (method_exists($controller, $action_name)) {
                return $controller->$action_name(); // lead controller method
            } else {
                throw new Exception('Class method does not exists'); // throw exception
            }*/



        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n"; // echo the exception. TODO : update that to be logged and not displayed
            //$this->load('ControllerErrorNot_found');
        }
    }

    private function rewrite($rules) {
        // TODO : currently made to just get the name and set it as 1 key, for future reference make it follow a pattern, eg. : "/:id/add/:foo", currently a lot of work, and really can't think of any implementations for it, but still.
        if(isset($rules[$this->method])){
            $key = trim($rules[$this->method], '/');
            $_GET[$key] = $this->arguments;
            return true;
        }
    }

    private function getClassName() {
        return trim(ucfirst(CONTROLLER), DS) . implode(array_map("ucfirst", $this->controller_path)) . ucfirst($this->controller);
    }
}