<?php

class Controller {
    /* TODO : rework storage methods in separate library class
     * TODO : rework autoloader, it loads multiple times head class (currently) and does not support constructor arguments. Maybe make it load the library on "$this->x" request, before that only include the files. Also on first "$this->x(args)" send constructor arguments.
     */
    protected $load;

    public function __construct(){
        $this->load = new Loader();

        $this->autoload();
    }

    public function redirect($url, $data = array()){
        header("Location:$url");
        if(!empty($data)) {
            $this->store($data);
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
                    $name = strtolower($name);
                    $this->$name = new $name();
                }
            }
            $it->next();
        }
    }

    private function store($data) {
        $_SESSION['storage_data'] = $data;
    }

    protected function getStorage() {
        if(session_id() != '') {
            $data = $_SESSION['storage_data'];
            unset($_SESSION['storage_data']);
            return $data;
        }
        return null;
    }
}