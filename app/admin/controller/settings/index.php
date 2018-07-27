<?php

class ControllerSettingsIndex extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $model = $this->load->model('settings/settings');

            $data['header'] = $this->load->controller('common/header/index', 'Settings');

            $data['footer'] = $this->load->view('common/footer');

            $session_storage_data = $this->getStorage();
            if(!empty($session_storage_data)) {
                $data['messages'] = $session_storage_data;
            }

            $data['settings'] = $model->getSettings();
            $data['add_link'] = $this->url->admin . "/settings/index/add";
            $data['edit_link'] = $this->url->admin . "/settings?id=";
            $data['delete_link'] = $this->url->admin . "/settings/delete?id=";

            return $this->load->view('settings/settings_list', $data);
        } else {
            $this->redirect('/admin');
        }
    }
}