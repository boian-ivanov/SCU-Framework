<?php

class ControllerCatalogIndex extends Controller {

    public function index() {
        $data['header'] = $this->load->controller('common/header/index');
        $data['footer'] = $this->load->controller('common/footer/index');

        // our services are loaded here     

        return $this->load->view('catalog/index', $data);
    }
}