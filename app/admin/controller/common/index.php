<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => $this->url->root . '/public/css/bootstrap.min.css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => $this->url->root . '/public/css/font-awesome.min.css'
        ]);

        $this->head->addScript($this->url->root . '/public/js/jquery-3.2.1.min.js');
        $this->head->addScript($this->url->root . '/public/js/bootstrap.min.js');

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        $data['form_link'] = $this->url->root . '/admin/common/login';
        $data['forgotten_link'] = $this->url->root . '/admin/common/reset';

        $data['root_url'] = $this->url->root;

        /*  TODO : URL library for url generation
        $data['form_link'] = $this->url->generate('common/login') . 'common/login';*/

        $data['hello'] = "Welcome to SCU Framework Admin Panel";

        $data['data'] = "You have entered the admin panel";

        $data['header'] = $this->load->view('common/header', $data);

        $data['footer'] = $this->load->view('common/footer', $data);

        return $this->load->view('common/index', $data);
    }
}