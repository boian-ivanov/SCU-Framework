<?php

class ControllerSettingsTeam extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
//            $this->load->model('');



            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            if(isset($_GET['id']) && $_GET['id'] != ''){
                $data['name'] = 'Test';
                $data['form_post_link'] = $this->url->admin . "/settings/team/edit";

                return $this->load->view('settings/team_form', $data);
            } else {
                return $this->load->view('settings/team', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function create() {

    }

    public function edit() {
        if(!empty($_FILES)) {
            $this->fileUpload->setter($_FILES['profileImage'], PUBLIC_PATH . 'images/profile_images/');
            try {
                $image_name = $this->fileUpload->upload();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }


    }

    public function delete() {

    }
}