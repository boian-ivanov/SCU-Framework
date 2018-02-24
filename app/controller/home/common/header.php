<?php

class ControllerCommonHeader extends Controller {
    /*private $registry;

    public function __construct($registry) {
        parent::__construct();
        $this->registry = $registry;
    }*/

    public function index() {
        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/bootstrap.min.css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/font-awesome.min.css'
        ]);

        $this->head->addScript('/public/js/jquery-3.2.1.min.js');
        $this->head->addScript('/public/js/bootstrap.min.js');

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        return $this->load->view('common/header', $data);
    }
}