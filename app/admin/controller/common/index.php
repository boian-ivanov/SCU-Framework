<?php

class ControllerCommonIndex extends Controller {
    private $user;

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['nav'] = $this->load->controller('common/snippets/navbar', 'Dashboard');

            $data['sidebar'] = $this->load->controller('common/snippets/sidenav');

            $model = $this->load->model('common/index');

            $user_model = $this->load->model('account/user'); // load user model & data
            $this->user = $user_model->getUserById($_SESSION['user_id']);

            // Admin Dashboard Logic
            $data['welcome'] = sprintf($model->getWelcomeMessage(), $this->user->display_name);

            $view_path = 'common/index';
        } else {
            $data['root_url'] = $this->url->root;

            $data['form_link'] = $this->url->admin . '/account/login'; // TODO : maybe rework url library
            $data['forgotten_link'] = $this->url->admin . '/account/reset';

            if(isset($_SESSION['error'])) {
                $data['error'] = $_SESSION['error'];
                unset($_SESSION['error']);
            }

            $view_path = 'common/login';
        }

        $data['header'] = $this->load->controller('common/snippets/header');

        $data['footer'] = $this->load->view('common/footer', $data); // TODO : at some point maybe load footer from controller too (maybe)

        return $this->load->view($view_path, $data);
    }
}