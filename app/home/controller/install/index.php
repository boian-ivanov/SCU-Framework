<?php

class ControllerInstallIndex extends Controller {

    public function index() {
        return $this->load->view('');
    }

    public function _2() {
        $model = $this->load->model('install/dbinit');
        var_dump($model->createTables());
    }
}