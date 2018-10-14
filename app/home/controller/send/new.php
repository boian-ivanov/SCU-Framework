<?php

class ControllerSendNew extends Controller {

    public function mail() {
        $model = $this->load->model('mail/main');
        $request = $this->request->post;
        $data = [];
        if(!empty($request)) {
            if ($this->validate($request) && $model->storeMail($request)) { // succeeds to send email
                $data['success'] = true; // send true response
            } else {
                $data['error'] = false; // send false response
            }
        }
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($data);
        die();
        $this->redirect($this->url->root, $data);
    }

    private function validate (&$data) {
        $required = array('office', 'name', 'email', 'telephone', 'message');
        foreach ($data as $key => $value) { // remove unnecessary key value pairs
            if(!in_array($key, $required))
                unset($data[$key]);
        }
        $missing = array_diff_key(array_flip($required), $data); // check if some are missing

        return empty($missing); // return a bool value
    }
}