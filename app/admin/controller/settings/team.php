<?php

class ControllerSettingsTeam extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
//            $this->load->model('');

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            $model = $this->load->model('settings/team');
            if(isset($this->request->get['id']) && $this->request->get['id'] != ''){
                $id = $this->request->get['id'];

                $data['member_data'] = $model->getMemberData($id);
                $data['image_path'] = $this->url->root . '/public/images/profile_images/';
                $data['form_post_link'] = $this->url->admin . "/settings/team/edit?id=" . $id;

                return $this->load->view('settings/team_form', $data);
            } else {
                $session_storage_data = $this->getStorage();
                if(!empty($session_storage_data)) {
                    $data['messages'] = $session_storage_data;
                }

                $data['members'] = $model->getMembers();
                $data['edit_link'] = $this->url->admin . "/settings/team?id=";
                $data['delete_link'] = $this->url->admin . "/settings/team/delete?id=";

                return $this->load->view('settings/team', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function create() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
//            $this->load->model('');

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            if(!empty($this->request->post)) {

            } else {
                $data['form_post_link'] = $this->url->admin . "/settings/team/create";
                return $this->load->view('settings/team_form', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function edit() {
        $messages = array();
        if(isset($this->request->get['id'])) {
            $model = $this->load->model('settings/team');
            if($this->request->files['profileImage']['name'] != '') { // image file update
                $this->fileupload->setter($this->request->files['profileImage'], PUBLIC_PATH . 'images/profile_images/');
                try {
                    $image_name = $this->fileupload->upload();
                    if($res = $model->updateMemberImage($this->request->get['id'], $image_name)){
                        $messages['success'][] = "Image has been updated successfully.";
                    } else {
                        $messages['error'][] = "Image has not been updated. Please try again.";
                    }
                } catch (Exception $e) {
                    $messages['error'][] = $e->getMessage();
                }
            }
            if(!empty($this->request->post)) {
                if($model->updateMemberData($this->request->get['id'], $this->request->post)){
                    $messages['success'][] = "Member data has been updated.";
                }
            }
        }

        $this->redirect($this->url->admin . "/settings/team", $messages);
    }

    public function delete() {

    }
}