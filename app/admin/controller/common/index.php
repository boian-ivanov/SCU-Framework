<?php

class ControllerCommonIndex extends Controller {
    private $user;

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $model = $this->load->model('common/index');

            $user_model = $this->load->model('account/user'); // load user model & data
            $this->user = $user_model->getUserById($_SESSION['user_id']);

            // Admin Dashboard Logic
            $data['welcome'] = sprintf($model->getWelcomeMessage(), $this->user->display_name);

            if(!$_SERVER['HTTP_ASYNC']) {
                $data['header'] = $this->load->controller('common/header/index', 'Dashboard');
                $data['footer'] = $this->load->view('common/footer');
            }

            return $this->load->view('common/index', $data);
        } else {
            $data['root_url'] = $this->url->root;

            $data['form_link'] = $this->url->admin . '/account/login'; // TODO : maybe rework url library
            $data['forgotten_link'] = $this->url->admin . '/account/reset';

            if (isset($_SESSION['error'])) {
                $data['error'] = $_SESSION['error'];
                unset($_SESSION['error']);
            }

            $data['header'] = $this->load->controller('common/snippets/header');
            $data['footer'] = $this->load->view('common/footer');

            return $this->load->view('common/login', $data);
        }
    }
}