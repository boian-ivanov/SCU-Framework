<?php

class ControllerSettingsTeam extends Controller {

    public function __rewrite() {
        return array(
            'edit' => '/id',
            'view' => '/id'
        );
    }

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

                return $this->load->view('settings/team_form', $data);
            } else {
                $session_storage_data = $this->getStorage();
                if(!empty($session_storage_data)) {
                    $data['messages'] = $session_storage_data;
                }

                $data['members'] = $this->processMembers($model->getMembers());
                $data['add_link'] = $this->url->admin . "/settings/team/add";
                $data['edit_link'] = $this->url->admin . "/settings/team/view/";
                $data['delete_link'] = $this->url->admin . "/settings/team/delete?id=";

                return $this->load->view('settings/team_list', $data);
            }
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            if(!empty($this->request->post)) {
                $model = $this->load->model('settings/team');

                $validated_data = $this->validate($this->request->post);

                if($this->request->files['profileImage']['name'] != '') { // image file update
                    $upload = $this->uploadFile($this->request->files['profileImage']);
                    $messages = $upload['message'];
                    if(isset($upload['file_name'])) {
                        $validated_data = array_merge($validated_data, ['image' => $upload['file_name']]);
                    }
                }

                if($this->request->files['profileBackgroundImage']['name'] != '') { // background image file update
                    $upload = $this->uploadFile($this->request->files['profileBackgroundImage'], [
                        "x" => 1280,
                        "y" => 500
                    ]);
                    $messages = array_merge(($messages ?? []), $upload['message']);
                    if(isset($upload['file_name'])) {
                        $validated_data = array_merge($validated_data, ['background_image' => $upload['file_name']]);
                    }
                }

                if(!empty($validated_data) && $id = $model->addMember($validated_data)) {
                    $messages['success'][] = "Member with ID : '" . $id . "' has been added";
                } else {
                    $messages['error'][] = "Error occurred. Member has not been added.";
                }

                $this->redirect($this->url->admin . "/settings/team/index", $messages);
            } else {
                $data['title'] = "Add a member";
                $data['form_post_link'] = $this->url->admin . "/settings/team/add";
                return $this->load->view('settings/team_form', $data);
            }
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function edit() {
        $messages = array();
        if(isset($this->request->get['id'])) {
            $model = $this->load->model('settings/team');
            if($this->request->files['profileImage']['name'] != '') { // image file update
                $upload = $this->uploadFile($this->request->files['profileImage']);
                $messages = $upload['message'];
                if(isset($upload['file_name'])) {
                    $res = $model->updateMemberImage($this->request->get['id'], $this->upload->file_dst_name);
                }
            }

            if($this->request->files['profileBackgroundImage']['name'] != '') { // background image file update
                $upload = $this->uploadFile($this->request->files['profileBackgroundImage'], [
                    "x" => 1280,
                    "y" => 500
                ]);
                $messages = array_merge(($messages ?? []), $upload['message']);
                if(isset($upload['file_name'])) {
                    $res = $model->updateMemberBackgroundImage($this->request->get['id'], $this->upload->file_dst_name);
                }
            }

            if(!empty($this->request->post)) {
                if($model->updateMemberData($this->request->get['id'], $this->validate($this->request->post))){
                    $messages['success'][] = "Member data has been updated.";
                }
            }
        }

        $this->redirect($this->url->admin . "/settings/team/index", $messages);
    }

    public function view() {
        $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
        $data['footer'] = $this->load->view('common/footer');

        $model = $this->load->model('settings/team');
        $id = $this->request->get['id'];

        $data['member_data'] = $model->getMemberData($id);
        $data['image_path'] = $this->url->root . '/public/images/profile_images/';
        $data['form_post_link'] = $this->url->admin . "/settings/team/edit/" . $id;

        $data['title'] = "Edit profile";

        return $this->load->view('settings/team_form', $data);
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
            $this->redirect($this->url->admin . "/settings/team/index", $messages);
        } else {
            $this->redirect($this->url->admin);
        }
    }

    private function validate($data) {
        $allowed_fields = ['name', 'short_description', 'description', 'url_slug', 'active'];
        $return = array();
        foreach($data as $key => $item) {
            if(in_array($key, $allowed_fields)) {
                $return[$key] = filter_var($item, FILTER_SANITIZE_STRING);
            }
        }
        if(isset($data['active']))
            $return['active'] = 1;
        else
            $return['active'] = 0;

        return $return;
    }

    private function processMembers($members, $length = '50') {
        foreach($members as $key => $member) {
            if($member['short_description'] != ''
                && $member['short_description'] != null
                && mb_strlen($member['short_description']) > $length) {
                $members[$key]['short_description'] = mb_substr(trim($member['short_description']), 0, $length) . "&hellip;";
            }
        }
        return $members;
    }

    private function uploadFile($fileObject, $options = array()) {
        $defaults = [
            "file_name" => md5(date('now')),
            "resize" => true,
            "ratio_crop" => true,
            "x" => "250",
            "y" => "250",
        ];

        if(!empty($options)) {
            $defaults = array_replace($defaults, $options);
        }

        $this->upload($fileObject);
        if($this->upload->uploaded && !$this->upload->error){
            $this->upload->file_new_name_body = $defaults['file_name'];
            $this->upload->image_resize = $defaults['resize'];
            $this->upload->image_ratio_crop = $defaults['ratio_crop'];
            $this->upload->image_x = $defaults['x'];
            $this->upload->image_y = $defaults['y'];
            $this->upload->process(PUBLIC_PATH . 'images/profile_images/');
            if($this->upload->processed){
                $response['message']['success'][] = "Image has been uploaded successfully.";
                $response['file_name'] = $this->upload->file_dst_name;
                $this->upload->clean();
            } else {
                $response['message']['error'][] = "Image has not been uploaded. Please try again. [Error : ".$this->upload->error."]";
            }
        } else {
            $response['message']['error'][] = $this->upload->error;
        }
        return $response;
    }
}