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

        $this->head->addLinks([
            'rel'  => 'stylesheet',
            'href' => $this->url->root . '/public/css/master.css'
        ]);

        $this->head->addScript($this->url->root . '/public/js/jquery-3.2.1.min.js');
        $this->head->addScript($this->url->root . '/public/js/bootstrap.min.js');

        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $this->head->addLinks([
                'rel'  => 'stylesheet',
                'href' => $this->url->root . '/public/css/admin.css'
            ]);

            $this->head->addScript($this->url->root . '/public/js/admin.js');

            $data['nav'] = $this->getNav();

            $data['sidebar'] = $this->getSideNav();

            $view_path = 'common/index';
        } else {
            $data['root_url'] = $this->url->root;

            $data['form_link'] = $this->url->root . '/admin/account/login'; // TODO : maybe rework url library
            $data['forgotten_link'] = $this->url->root . '/admin/account/reset';

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

    public function getNav() {
        $model = $this->load->model('account/user');

        $user = $model->getUserById($_SESSION['user_id']);

        $data['display_name'] = $user->display_name;

        return $this->load->view('common/nav', $data);
    }

    public function getSideNav() {
        /*$model = $this->load->model('account/user');

        $user = $model->getUserById($_SESSION['user_id']);*/

        $data = [];

        return $this->load->view('common/sidebar', $data);
    }
}