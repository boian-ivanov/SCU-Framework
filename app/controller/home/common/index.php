<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        $model = $this->load->model('common/index');

        $model->index();
    }
}