<?php

class Controller {
    /* TODO : rework storage methods in separate library class
     * TODO : rework autoloader, it loads multiple times head class (currently) and does not support constructor arguments. Maybe make it load the library on "$this->x" request, before that only include the files. Also on first "$this->x(args)" send constructor arguments.
     */
    protected $load;

    public function __construct(){
        $this->load = new Loader();
    }

    public function redirect($url, $data = array()){
        header("Location:$url");
        if(!empty($data)) {
            $this->store($data);
        }
        exit;
    }

    private function store($data) {
        $_SESSION['storage_data'] = $data;
    }

    protected function getStorage() {
        if(session_id() != '' && isset($_SESSION['storage_data'])) {
            $data = $_SESSION['storage_data'];
            unset($_SESSION['storage_data']);
            return $data;
        }
        return null;
    }

    /*public function __set($name, $value) {
        // TODO: Implement __set() method.
//        $this->$name = $value;
    }*/

    public function __call($name, $arguments) {
        // TODO: Implement __call() method.
        return $this->$name = $this->load->$name($arguments);
    }

    public function __get($name) {
        // TODO: Implement __get() method.
        return $this->$name = $this->load->$name;
    }
}