<?php

class ControllerCommonIndex extends Controller{
    private $registry;

    public function __construct($registry){
        parent::__construct();
        $this->registry = $registry;
    }

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/

//        $this->registry->set('foo', 'bar');



        $data['hello'] = "Hellow worlds";
        $data['data'] = 'diz iz views, iz last mvc step. mi iz hepi';
        $data['heading'] = 'Me iz title';

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => 'public/css/shoelace.css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/font-awesome.min.css'
        ]);

        $this->head->addScript('public/js/jquery-3.2.1.min.js');
        $this->head->addScript('public/js/shoelace.js');

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        $data['header'] = $this->load->view('common/header', $data);
        $data['top_wrapper'] = $this->load->view('common/top_wrapper', $data);

        $data['footer'] = $this->load->view('common/footer', $data);

        echo $this->load->view('common/index', $data);
    }
}