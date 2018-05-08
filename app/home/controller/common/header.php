<?php

class ControllerCommonHeader extends Controller {
    /*private $registry;

    public function __construct($registry) {
        parent::__construct();
        $this->registry = $registry;
    }*/

    public function index() {
        $this->head->addLinks([
            'rel' => 'stylesheet',
            'href' => "https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css",
            'integrity' => "sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4",
            'crossorigin' =>"anonymous"
        ]);

        $this->head->addLinks('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        $this->head->addLinks($this->url->root . '/public/css/master.css');

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

        $this->head->addScript($this->url->root . '/public/js/jquery.min.js');
        $this->head->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
        $this->head->addScript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js');
        $this->head->addScript($this->url->root . '/public/js/jquery.bcSwipe.min.js');
        $this->head->addScript($this->url->root . '/public/js/main.js');

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