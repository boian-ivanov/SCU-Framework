<?php

class ControllerCommonFooter extends Controller {


    public function index() {

        $data = array();

        return $this->load->view('common/footer', $data);
    }
}