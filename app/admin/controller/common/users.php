<?php

class ControllerCommonUsers extends Controller {

    public function __rewrite() {
        return array(
//            'edit' => '/id',
            'view' => '/id'
        );
    }

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $data['header'] = $this->load->controller('common/header/index', 'Users');
            $data['footer'] = $this->load->view('common/footer');

            $model = $this->load->model('common/users');
            $data['users'] = $model->getUsers();

            $data['add_link'] = $this->url->admin . "/users/add";
            $data['edit_link'] = $this->url->admin . "/users/view/";
            $data['delete_link'] = $this->url->admin . "/users/delete?id=";

            return $this->load->view('users/index', $data);
        } else {
            $this->redirect('/admin');
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            $data['header'] = $this->load->controller('common/header/index', 'Settings > Team');
            $data['footer'] = $this->load->view('common/footer');

            if (!empty($this->request->post)) {

                $this->redirect($this->url->admin . "/users");
            } else {
                $data['title'] = "Add a user";
                $data['form_post_link'] = $this->url->admin . "/users/add";
                return $this->load->view('settings/testimonials_form', $data);
            }
        } else {
            $this->redirect('/admin');
        }
    }

    public function view() {
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($_GET);
        die();
    }

    public function delete() {
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($_GET);
        die();
    }
}