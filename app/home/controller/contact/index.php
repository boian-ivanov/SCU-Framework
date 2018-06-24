<?php

class ControllerContactIndex extends Controller {

    public function index() {
        $data['header'] = $this->load->controller('common/header/index');
        $data['footer'] = $this->load->controller('common/footer/index');

        return $this->load->view('contact/index', $data);
    }
}