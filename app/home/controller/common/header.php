<?php

class ControllerCommonHeader extends Controller {
    /*private $registry;

    public function __construct($registry) {
        parent::__construct();
        $this->registry = $registry;
    }*/
    /* https://realfavicongenerator.net/favicon_result?file_id=p1cfslp8chvhjtbk1t29jak18f97#.WyEjR0gvy71 */

    public function index() {
        $links = [
            '/node_modules/bootstrap/dist/css/bootstrap.min.css',
            '/node_modules/font-awesome/css/font-awesome.min.css',
            'http://fonts.googleapis.com/css?family=Roboto:400,700',
            'http://fonts.googleapis.com/css?family=Open+Sans',
            'https://fonts.googleapis.com/css?family=Lobster|Oswald|Raleway|PT+Sans&subset=latin,cyrillic',
            $this->url->root . '/public/css/master.css',
            $this->url->root . '/public/css/extended.css',
        ];

        $scripts = [
            '/node_modules/jquery/dist/jquery.min.js',
            '/node_modules/popper.js/dist/umd/popper.min.js',
            '/node_modules/bootstrap/dist/js/bootstrap.min.js',
            '/node_modules/moment/moment.js',
            $this->url->root . '/public/js/jquery.bcSwipe.min.js',
            $this->url->root . '/public/js/main.js',
//            $this->url->root . '/public/js/speed.js'
        ];

        $data['links'] = $this->head->addLinks($links);
        $data['scripts'] = $this->head->addScripts($scripts);

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