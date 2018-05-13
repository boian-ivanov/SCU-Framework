<?php

class ControllerSettingsIndex extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
//            $this->load->model('');

            $data['header'] = $this->load->controller('common/header/index', 'Settings');

            $data['footer'] = $this->load->view('common/footer');

            return $this->load->view('settings/index', $data);
        } else {
            $this->redirect('/admin');
        }
    }
}