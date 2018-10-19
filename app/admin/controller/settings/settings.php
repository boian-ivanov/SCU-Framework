<?php

class ControllerSettingsSettings extends Controller {

    public function index() {
        $this->redirect($this->url->admin . "/settings/settings/view");
    }

    public function edit() {
        $messages = array();
        if(isset($this->request->get['id'])) {
            $model = $this->load->model('settings/index');
            if(!empty($this->request->post)) {
                if($model->updateMemberData($this->request->get['id'], $this->validate($this->request->post))){
                    $messages['success'][] = "Member data has been updated.";
                }
            }
        }

        $this->redirect($this->url->admin . "/settings/settings/view", $messages);
    }

    public function view() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['header'] = $this->load->controller('common/header/index', 'Settings > Settings');
            $data['footer'] = $this->load->view('common/footer');

            $model = $this->load->model('settings/settings');

            $data['setting_data'] = $model->getSettingData();

            $this->form->loadData($data['setting_data']['data']);
            $data['setting_data']['data'] = $this->form->getForm();

            $data['form_post_link'] = $this->url->admin . "/settings/settings/edit/" . $id;

            $data['title'] = "Edit setting";
            return $this->load->view('settings/setting_form', $data);
        } else {
            $this->redirect('/admin');
        }
    }
}