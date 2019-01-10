<?php

class ControllerAccountLogin extends Controller {

    public function index() {
        if(session_id() === '') {
            session_start();
        }
        try {
            $model = $this->load->model('account/user'); // Load user model
            // Get email address from request body
            $email = filter_input(INPUT_POST, 'email'); //'foo@bar.baz';
            // Get password from request body
            $password = filter_input(INPUT_POST, 'password'); //'123456';
            // Get user data
            if(!$user = $model->findByEmail($email)) {
                throw new Exception('User not found');
            }
            // Verify password with account password hash
            if (password_verify($password, $user->passcode) === false) {
                throw new Exception('Invalid password');
            }
            // Re-hash password if necessary
            $currentHashAlgorithm = PASSWORD_DEFAULT;
            $currentHashOptions = array('cost' => 15);
            $passwordNeedsRehash = password_needs_rehash(
                $user->passcode,
                $currentHashAlgorithm,
                $currentHashOptions
            );
            if ($passwordNeedsRehash === true) {
                // Save new password hash
                $password_hash = password_hash(
                    $password,
                    $currentHashAlgorithm,
                    $currentHashOptions
                );
                $model->save($user->user_id, ['passcode' => $password_hash]);
            }

            // Save login status to session
            $_SESSION['user_logged_in'] = 'yes';
            $_SESSION['user_id'] = $user->user_id;

            // Redirect to profile page
        } catch (Exception $e) {
//            header('HTTP/1.1 401 Unauthorized');
            $_SESSION['error'] = $e->getMessage();
        }
        $location = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin';
        header('HTTP/1.1 302 Redirect');
        header('Location: ' . $location);
    }
}