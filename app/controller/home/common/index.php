<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/
        $data['hello'] = "Hellow worlds";

        return $this->load->view('common/index', $data);
    }
}