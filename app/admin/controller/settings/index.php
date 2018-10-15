<?php

class ControllerSettingsIndex extends Controller {

    public function __rewrite() {
        return array(
            'edit' => '/id',
            'view' => '/id'
        );
    }

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

            $data['add_link'] = $this->url->admin . "/settings/index/add";
            $data['edit_link'] = $this->url->admin . "/settings/index/view/";
            $data['delete_link'] = $this->url->admin . "/settings/index/delete?id=";

            return $this->load->view('settings/settings_list', $data);
        } else {
            $this->redirect('/admin');
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Setting');
            $data['footer'] = $this->load->view('common/footer');

            if(!empty($this->request->post)) {
                $model = $this->load->model('settings/settings');



//                $validated_data = $this->validate($this->request->post);
//                if(isset($image_name)) $validated_data = array_merge($validated_data, ['image'=> $image_name]);

                if(!empty($validated_data) && $id = $model->addMember($validated_data)) {
                    $messages['success'][] = "Member with ID : '" . $id . "' has been added";
                } else {
                    $messages['error'][] = "Error occurred. Member has not been added.";
                }

                $this->redirect($this->url->admin . "/settings/index/index", $messages);
            } else {
                $data['title'] = "Add a member";
                $data['form_post_link'] = $this->url->admin . "/settings/index/add";
                return $this->load->view('settings/index_form', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function edit() {
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($_POST);
        die();
        $messages = array();
        if(isset($this->request->get['id'])) {
            $model = $this->load->model('settings/index');
            if(!empty($this->request->post)) {
                if($model->updateMemberData($this->request->get['id'], $this->validate($this->request->post))){
                    $messages['success'][] = "Member data has been updated.";
                }
            }
        }

        $this->redirect($this->url->admin . "/settings/index/index", $messages);
    }

    public function view() {
        $data['header'] = $this->load->controller('common/header/index', 'Settings > Settings');
        $data['footer'] = $this->load->view('common/footer');

        $model = $this->load->model('settings/settings');
        $id = $this->request->get['id'];

        $data['setting_data'] = $model->getSettingData($id);

        if(!$data['setting_data']) $this->redirect($this->url->admin . "/settings/index/index");

        $this->form->loadData($data['setting_data']['data']);
        $data['setting_data']['data'] = $this->form->getForm();

        $data['form_post_link'] = $this->url->admin . "/settings/index/edit/" . $id;

        $data['title'] = "Edit setting";
        return $this->load->view('settings/setting_form', $data);
    }

    public function delete() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $messages = [];
            if(isset($this->request->get['id'])) {
                $model = $this->load->model('settings/settings');
                // TODO : add authorisation before delete action

                $id = $this->request->get['id'];
                if($model->deleteMember($id)){
                    $messages['success'][] = "Member with ID :'$id' has been deleted successfully.";
                } else {
                    $messages['error'][] = "Member with ID :'$id' has NOT been deleted.";
                }
            }
            $this->redirect($this->url->admin . "/settings/index/index", $messages);
        } else {
            $this->redirect('/admin');
        }
    }
}