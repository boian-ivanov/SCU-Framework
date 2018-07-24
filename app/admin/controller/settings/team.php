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

                $data['title'] = "Edit profile";
                //if (isset($member_data['name'])) echo \" <span>\" . $member_data['name'] . \"'s</span>\"

                return $this->load->view('settings/team_form', $data);
            } else {
                $session_storage_data = $this->getStorage();
                if(!empty($session_storage_data)) {
                    $data['messages'] = $session_storage_data;
                }

                $data['members'] = $model->getMembers();
                $data['add_link'] = $this->url->admin . "/settings/team/add";
                $data['edit_link'] = $this->url->admin . "/settings/team?id=";
                $data['delete_link'] = $this->url->admin . "/settings/team/delete?id=";

                return $this->load->view('settings/team', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            if(!empty($this->request->post)) {
                $model = $this->load->model('settings/team');

                if($this->request->files['profileImage']['name'] != '') { // image file update
                    $this->fileupload->setter($this->request->files['profileImage'], PUBLIC_PATH . 'images/profile_images/');
                    try {
                        if($image_name = $this->fileupload->upload()){
                            $messages['success'][] = "Image has been uploaded successfully.";
                        } else {
                            $messages['error'][] = "Image has not been uploaded. Please try again.";
                        }
                    } catch (Exception $e) {
                        $messages['error'][] = $e->getMessage();
                    }
                }

                $validated_data = $this->validate($this->request->post);
                if(isset($image_name)) $validated_data = array_merge($validated_data, ['image'=> $image_name]);

                if(!empty($validated_data) && $id = $model->addMember($validated_data)) {
                    $messages['success'][] = "Member with ID : '" . $id . "' has been added";
                } else {
                    $messages['error'][] = "Error occurred. Member has not been added.";
                }

                $this->redirect($this->url->admin . "/settings/team", $messages);
            } else {
                $data['title'] = "Add a member";
                $data['form_post_link'] = $this->url->admin . "/settings/team/add";
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
                if($model->updateMemberData($this->request->get['id'], $this->validate($this->request->post))){
                    $messages['success'][] = "Member data has been updated.";
                }
            }
        }

        $this->redirect($this->url->admin . "/settings/team", $messages);
    }

    public function delete() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $messages = [];
            if(isset($this->request->get['id'])) {
                $model = $this->load->model('settings/team');
                // TODO : add authorisation before delete action

                $id = $this->request->get['id'];
                if($model->deleteMember($id)){
                    $messages['success'][] = "Member with ID :'$id' has been deleted successfully.";
                } else {
                    $messages['error'][] = "Member with ID :'$id' has NOT been deleted.";
                }
            }
            $this->redirect($this->url->admin . "/settings/team", $messages);
        } else {
            $this->redirect('/admin');
        }
    }

    private function validate($data) {
        $allowed_fields = ['name', 'short_description', 'description'];
        $return = array();
        foreach($data as $key => $item) {
            if(in_array($key, $allowed_fields)) {
                $return[$key] = filter_var($item, FILTER_SANITIZE_STRING);
            }
        }
        return $return;
    }
}