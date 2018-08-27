<?php

class ControllerCommonContact extends Controller {

    public function index() {
        $data['header'] = $this->load->controller('common/header/index');
        $data['footer'] = $this->load->controller('common/footer/index');

        $data['map'] = $this->load->view('contact/map');
        $data['form'] = $this->load->view('contact/form');

        return $this->load->view('contact/index', $data);
    }
}