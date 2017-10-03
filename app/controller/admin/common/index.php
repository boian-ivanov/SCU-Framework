
<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/shoelace.css'
        ]);

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => '/public/css/font-awesome.min.css'
        ]);

        $this->head->addScript('public/js/jquery-3.2.1.min.js');
//        $this->head->addScript('http://zeptojs.com/zepto.min.js');
        $this->head->addScript('public/js/shoelace.js');

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        $data['form_link'] = $this->full_url($_SERVER) . '/common/login';
        $data['forgotten_link'] = $this->full_url($_SERVER) . '/common/reset';

        /*  TODO : URL library for url generation
        $data['form_link'] = $this->url->generate('common/login') . 'common/login';*/

        $data['header'] = $this->load->view('common/header', $data);

        $data['footer'] = $this->load->view('common/footer', $data);

        $data['data'] = "You have entered the admin panel";

        echo $this->load->view('common/index', $data);
    }
}