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

    public function classLoaded() {
        $trace = debug_backtrace();
        return $trace[2]['class'] == 'Loader';
    }

    private function store($data) {
        $_SESSION['storage_data'] = $data;
    }

    protected final function getStorage() {
        if(session_id() != '' && isset($_SESSION['storage_data'])) {
            $data = $_SESSION['storage_data'];
            unset($_SESSION['storage_data']);
            return $data;
        }
        return null;
    }

    public function __call($name, $arguments) {
        return $this->$name = $this->load->$name($arguments[0]);
    }

    public function __get($name) {
        return $this->$name = $this->load->$name;
    }
}