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

        $this->load->controller('common/header/index');
        $this->load->controller('common/top_wrapper/index');

        $data['footer'] = $this->load->view('common/footer', $data);

        return $this->load->view('common/index', $data);
    }
}