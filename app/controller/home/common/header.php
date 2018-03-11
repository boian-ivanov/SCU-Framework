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

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/master.css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => 'http://fonts.googleapis.com/css?family=Roboto:400,700',
            'type' => 'text/css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => 'http://fonts.googleapis.com/css?family=Open+Sans',
            'type' => 'text/css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => 'https://fonts.googleapis.com/css?family=Lobster|Oswald|Raleway|PT+Sans&subset=latin,cyrillic',
            'type' => 'text/css'
        ]);

        $this->head->addScript('/public/js/jquery-3.2.1.min.js');
        $this->head->addScript('/public/js/bootstrap.min.js');
        $this->head->addScript('/public/js/jquery.bcSwipe.min.js');
        /*$this->head->addScript('/public/js/jquery.paroller.min.js');*/

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        $nav['nav'] = [
            'Home' => '#body',
            'Work' => '#work',
            'Prices' => '',
            'About Us' => '',
            'Contacts' => ''
        ];

        $data['top_wrapper'] = $this->load->view('common/top_wrapper', $nav);

        return $this->load->view('common/header', $data);
    }
}