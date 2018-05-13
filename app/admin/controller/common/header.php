<?php

class ControllerCommonHeader extends Controller {

    public function index($title) {
        if(empty($title)) $title = '';
        $data['header'] = $this->load->controller('common/snippets/header');

        $data['nav'] = $this->load->controller('common/snippets/navbar', (string)$title);

        $data['sidebar'] = $this->load->controller('common/snippets/sidenav');

        return $this->load->view('common/header', $data);
    }
}