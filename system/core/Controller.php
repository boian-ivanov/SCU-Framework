<?php

class Controller {
    // TODO : a way to redirect to a controller with included data
    protected $load;

    public function __construct(){
        $this->load = new Loader();

        $this->autoload();
    }

    public function redirect($url, $wait = 0){
        if ($wait == 0){
            header("Location:$url");
        }
        exit;
    }

    private function autoload() {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(LIB_PATH));

        while($it->valid()) {
            if (!$it->isDot()) {
                if(file_exists($it->key()) && $it->valid()) {
                    require_once($it->key());
                    $name = pathinfo($it->getSubPathName(), PATHINFO_FILENAME);
                    $this->$name = new $name();
                }
            }
            $it->next();
        }
    }
}