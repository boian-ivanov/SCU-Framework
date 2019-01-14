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

            $session_storage_data = $this->getStorage();
            if(!empty($session_storage_data)) {
                $data['messages'] = $session_storage_data;
            }

            $data['add_link'] = $this->url->admin . "/users/add";
            $data['edit_link'] = $this->url->admin . "/users/edit/";
            $data['delete_link'] = $this->url->admin . "/users/delete?id=";

            return $this->load->view('users/index', $data);
        } else {
            $this->redirect($this->url->admin);
        }
    }

    public function add() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            if (!empty($this->request->post)) {
                $res = $this->createUser($this->request->post);

                if(isset($res['error'])) {
                    $redirect_url = $this->url->admin . "/users/add"; // go back
                } else {
                    $redirect_url = $this->url->admin . "/users";
                }
                $this->redirect($redirect_url, $res);
            } else {
                $data['title'] = "Add a user";
                $data['header'] = $this->load->controller('common/header/index', $data['title']);
                $data['footer'] = $this->load->view('common/footer');

                $session_storage_data = $this->getStorage();
                if(!empty($session_storage_data)) {
                    $data['messages'] = $session_storage_data;
                }

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
                $model = $this->load->model('account/user');
                try {
                    // User cannot change user's permissions, if he's the last one with those
                    //


                    if ($model->getUserCount($model->getUserPermissions($this->request->get['id'])) <= 1) {
                        throw new Exception("There are no more users with this status. User cannot be deleted");
                    }

                } catch (Exception $e) {

                }

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
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {
            $model = $this->load->model('account/user');
            try {
                if ($_SESSION['user_id'] == $this->request->get['id']) {
                    throw new Exception("You can not delete the current user!");
                }

                $currentUserPermissions = $model->getUserPermissions($_SESSION['user_id']);
                if ($currentUserPermissions !== "USER::ADMIN") {
                    throw new Exception("User does not have permission to delete this user.");
                }
                if ($model->getUserCount($model->getUserPermissions($this->request->get['id'])) <= 1) {
                    throw new Exception("There are no more users with this status. User cannot be deleted");
                }

                if (!isset($data['error']) && $model->deleteByUserId($this->request->get['id'])) {
                    $data['success'] = "User with id " . $this->request->get['id'] . " has been deleted successfully.";
                } else {
                    throw new Exception("User could not be deleted");
                }
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
            $this->redirect($this->url->admin . "/users", $data);
        } else {
            $this->redirect($this->url->admin);
        }
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
            if(!$model->statusExists($user_data['status'])) {
                throw new Exception('Status does not exists.');
            }
            // Verify password are not empty
            if ($user_data['password'] == "" || $user_data['confirm_password'] == "") {
                throw new Exception('Password field is empty!');
            }
            // Verify password are the same
            if ($user_data['password'] !== $user_data['confirm_password']) {
                throw new Exception('Passwords do not match!');
            }
            // Hash password if necessary
            $currentHashAlgorithm = PASSWORD_DEFAULT;
            $currentHashOptions = array('cost' => 15);

            $user_data['password_hash'] = password_hash(
                $user_data['password'],
                $currentHashAlgorithm,
                $currentHashOptions
            );
            $user_id = $model->create($user_data);
//            $model->save($user->user_id, ['passcode' => $password_hash]);
        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
        return array(
            'success' => "User with id '$user_id' has been created successfully"
        );
    }

    private function validateUserData(&$userData) {
        if(empty($userData)) return false;

        $required = array('email', 'display_name', 'status', 'password', 'confirm_password');
        foreach ($userData as $key => $value) { // remove unnecessary key value pairs
            if(!in_array($key, $required)) {
                unset($userData[$key]);
            }
        }

        if( empty($userData)
            && !isset($userData['email']) && $userData['email'] == ""
            && !isset($userData['display_name']) && $userData['display_name'] == ""
            && !isset($userData['status']) && !is_numeric($userData['status']) ) return false;

        $userData['email'] = filter_var($userData['email'], FILTER_SANITIZE_EMAIL);

        if(isset($userData['display_name']))
            $userData['display_name'] = filter_var($userData['display_name'], FILTER_SANITIZE_STRING);

        if(isset($userData['status']))
            $userData['status'] =       filter_var($userData['status'], FILTER_SANITIZE_NUMBER_INT);

        return true; // return a bool value
    }
}