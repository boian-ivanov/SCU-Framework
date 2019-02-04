<?php

class ControllerSendNew extends Controller {

    public function mail() {
        $model = $this->load->model('mail/main');
        $request = $this->request->post;
        $data = [];
        if(!empty($request)) {
            if ($this->validateCaptcha($request['grecaptcha_key']) // validate captcha
                && $this->validate($request) // validate input
                && $model->storeMail($request) // succeeds to store email
                && $this->sendMail($request)) { // succeeds to send email
                $data['success'] = true; // send true response
            } else {
                $data['error'] = false; // send false response
            }
        }
        $this->redirect($this->url->root, $data);
    }

    private function sendMail($data) {
        $from = 'mail@easydent.bg';
        $to = $data['email'];
        $subject = 'Testing mail';

        $message = $this->prepareTemplate($data);

//        $headers = "From:".$from;
        $headers  = array(
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=iso-8859-1',
            'Content-Transfer-Encoding: 7bit',
            'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
            'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
            'From: ' . $from,
            'Reply-To: ' . $from,
            'Return-Path: ' . $from,
            'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
            'Subject: '.$subject,
        );
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump(mail($to,$subject,$message, $headers));
        die();
        return mail($to,$subject,$message, $headers);
    }

    private function prepareTemplate ($data, $template = 'generic') {
        $data['img_path'] = $this->url->home . '/public/images/logo.svg';

        return $this->load->view('email/' . $template, $data);
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

    private function validateCaptcha ($token) {
        $model = $this->load->model('settings/settings');
        $settings = $model->getSettingData('general_settings'); // add settings key

        $postdata = [
            "secret" => $settings['captcha']['secret_key'],
            "response"=> $token
        ];

        if($_SERVER['REMOTE_ADDR']) $postdata["remoteip"] = $_SERVER['REMOTE_ADDR'];

        $postdata = http_build_query($postdata);

        $opts =
            ['http' =>
                [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                ]
        ];
        $context  = stream_context_create($opts);
        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $check = json_decode($result);
        return $check->success;
    }
}