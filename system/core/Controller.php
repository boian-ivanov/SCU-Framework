<?php

class Controller {
    // TODO : a way to redirect to a controller with included data
    protected $load;
    protected $head;
    protected $url;

    public function __construct(){
        $this->load = new Loader();

        $this->load->library('head');
        $this->head = new Head();

        $this->load->library('url');
        $this->url = new Url();
    }

    public function redirect($url, $wait = 0){
        if ($wait == 0){
            header("Location:$url");
        } /*else {
            include CURR_VIEW_PATH . "message.html";
        }*/
        exit;
    }
}