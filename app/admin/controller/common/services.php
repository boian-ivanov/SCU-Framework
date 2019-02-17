<?php

class ControllerCommonServices extends Controller {

    public function __rewrite() {
        return array(
            'edit' => '/id',
            'view' => '/id'
        );
    }

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['heading'] = 'Services';
            $data['header'] = $this->load->controller('common/header/index', 'Services');
            $data['footer'] = $this->load->view('common/footer');

            $model = $this->load->model('common/services');
            $data['services'] = $model->getServices();

            $session_storage_data = $this->getStorage();
            if(!empty($session_storage_data)) {
                $data['messages'] = $session_storage_data;
            }

            $data['add_link'] = $this->url->admin . "/services/add";
            $data['edit_link'] = $this->url->admin . "/services/view/";
            $data['delete_link'] = $this->url->admin . "/services/delete?id=";

            return $this->load->view('services/index', $data);
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['heading'] = 'Add a service';

            $data['header'] = $this->load->controller('common/header/index', $data['heading']);
            $data['footer'] = $this->load->view('common/footer');

            if(!empty($this->request->post)) {
                $model = $this->load->model('common/services');

                $validated_data = $this->validate($this->request->post);

                if(!empty($validated_data) && $id = $model->addService($validated_data)) {
                    $messages['success'] = "Service with ID : '" . $id . "' has been added";
                } else {
                    $messages['error'] = "Error occurred. Service has not been added.";
                }

                $this->redirect($this->url->admin . "/services");
            } else {
                $data['title'] = "Add a new service";
                $data['form_post_link'] = $this->url->admin . "/services/add";
                $data['font_url'] = $this->url->root . "/public/fonts/flaticon.html";
                return $this->load->view('services/service_form', $data);
            }
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function edit() {
        $messages = array();
        if(isset($this->request->get['id'])) {
            $id = $this->request->get['id'];
            $model = $this->load->model('common/services');
            if(!empty($this->request->post)
                && $model->updateServiceData($id, $this->validate($this->request->post))) {
                $messages['success'] = "Service data has been updated.";
            } else {
                $messages['error'] = "An error occurred while updating the service.";
            }
        }
        $this->redirect($this->url->admin . "/services", $messages);
    }

    public function view() {
        $data['title'] = "Edit service";
        $data['header'] = $this->load->controller('common/header/index', $data['title']);
        $data['footer'] = $this->load->view('common/footer');

        $model = $this->load->model('common/services');
        $id = $this->request->get['id'];

        $data['service_data'] = $model->getServiceData($id);
        $data['form_post_link'] = $this->url->admin . "/services/edit/" . $id;
        $data['font_url'] = $this->url->root . "/public/fonts/flaticon.html";

        return $this->load->view('services/service_form', $data);
    }

    public function delete() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $messages = [];
            if(isset($this->request->get['id'])) {
                $model = $this->load->model('common/services');
                // TODO : add authorisation before delete action
                $id = $this->request->get['id'];
                if($model->removeService($id)){
                    $messages['success'] = "Service with ID :'$id' has been deleted successfully.";
                } else {
                    $messages['error'] = "Service with ID :'$id' has NOT been deleted.";
                }
            }
            $this->redirect($this->url->admin . "/services", $messages);
        } else {
            $this->redirect($this->url->admin);
        }
    }

    private function validate($data) {
        $allowed_fields = ['text', 'description', 'icon', 'active'];
        $return = array();
        foreach($data as $key => $item) {
            if(in_array($key, $allowed_fields)) {
                $return[$key] = filter_var($item, FILTER_SANITIZE_STRING);
            }
        }
        if(isset($data['active']) && $data['active'] == 'on')
            $return['active'] = 1;
        else
            $return['active'] = 0;
        return $return;
    }
}