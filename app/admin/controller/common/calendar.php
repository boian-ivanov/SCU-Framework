<?php

class ControllerCommonCalendar extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {

            $data['header'] = $this->load->controller('common/header/index', 'Calendar');
            $data['footer'] = $this->load->view('common/footer');

            return $this->load->view('calendar/index', $data);
        } else {
            $this->redirect($this->url->admin);
        }
    }
}