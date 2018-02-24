<?php

class ControllerCommonTop_wrapper extends Controller {

    public function index() {

//        $data['head-img'] = "";

        $data['nav'] = [
            'Home' => '#body',
            'Work' => '#work',
            'Prices' => '',
            'About Us' => '',
            'Contacts' => ''
        ];

        return $this->load->view('common/top_wrapper', $data);
    }
}