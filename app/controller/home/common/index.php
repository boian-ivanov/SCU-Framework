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

        $data['header'] = $this->load->view('common/header', $data);
        $data['footer'] = $this->load->view('common/footer', $data);

        echo $this->load->view('common/index', $data);
    }
}