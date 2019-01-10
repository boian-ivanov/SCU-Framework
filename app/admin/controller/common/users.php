<?php

class ControllerCommonUsers extends Controller {

    public function __rewrite() {
        return array(
            'edit' => '/id'
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
            $this->redirect($this->url->admin);
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            if (!empty($this->request->post)) {
                $user = $this->load->model('account/user');
                $this->createUser($this->request->post);
                echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
                var_dump($this->request->post);
                die();

                $this->redirect($this->url->admin . "/users");
            } else {
                $data['title'] = "Add a user";
                $data['header'] = $this->load->controller('common/header/index', $data['title']);
                $data['footer'] = $this->load->view('common/footer');

                $model = $this->load->model('common/users');
                $data['user_status'] = $model->getUserStatus();

                $data['form_post_link'] = $this->url->admin . "/users/add";
                return $this->load->view('users/user_form', $data);
            }
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function edit() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $model = $this->load->model('account/user');

            if (!empty($this->request->post)) {


                $this->redirect($this->url->admin . "/users");
            } else {
                $data['title'] = "Edit user";
                $data['header'] = $this->load->controller('common/header/index', $data['title']);
                $data['footer'] = $this->load->view('common/footer');

                $data['user'] = $model->getUserById($this->request->get['id']);

                $data['form_post_link'] = $this->url->admin . "/users/edit";

                $users_model = $this->load->model('common/users');
                $data['user_status'] = $users_model->getUserStatus();

                return $this->load->view('users/user_form', $data);
            }
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function delete() {
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($_GET);
        die();
    }

    /*
     * Create user by posted data.
     *
     * return array [ success -> message ] || [ error -> error message ]
     */
    private function createUser(array $user_data) {
        try {
            if(!$this->validateUserData($user_data)) {
                throw new Exception('User data is not valid!');
            }

            $model = $this->load->model('account/user'); // Load user model
            // Get user data
            if($model->findByEmail($user_data['email'])) {
                throw new Exception('User already exists!');
            }
            // Verify password are the same
            if ($user_data['password'] !== $user_data['confirm_password']) {
                throw new Exception('Passwords do not match!');
            }
            // Hash password if necessary
            $currentHashAlgorithm = PASSWORD_DEFAULT;
            $currentHashOptions = array('cost' => 15);

            $password_hash = password_hash(
                $user_data['password'],
                $currentHashAlgorithm,
                $currentHashOptions
            );
            // TODO : Create a user record
//            $model->save($user->user_id, ['passcode' => $password_hash]);
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
        return array(
            'success' => "User has been created successfully"
        );
    }

    private function validateUserData(&$userData) {
        // TODO : Validate user input data
    }
}