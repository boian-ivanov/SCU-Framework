<?php

class ControllerCommonIndex extends Controller{

    public function index() {
        /*$model = $this->load->model('common/index');

        $model->index();*/

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

        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

//            $this->getDashboard($data);

            $view_path = 'common/index';
        } else {
            $data['root_url'] = $this->url->root;

            $data['form_link'] = $this->url->root . '/admin/account/login'; // TODO : maybe rework url library
            $data['forgotten_link'] = $this->url->root . '/admin/account/reset';

            $data['hello'] = "Welcome to SCU Framework Admin Panel";

            $data['data'] = "You have entered the admin panel";

            if(isset($_SESSION['error'])) {
                $data['error'] = $_SESSION['error'];
                unset($_SESSION['error']);
            }

            $view_path = 'common/login';
        }

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        $data['header'] = $this->load->view('common/header', $data);

        $data['footer'] = $this->load->view('common/footer', $data);

        return $this->load->view($view_path, $data);
    }

    protected function getDashboard(&$data) {

    }
}