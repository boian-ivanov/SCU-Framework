<?php

class ControllerCalendarIndex extends Controller {

    public function index() {
        if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == 'yes') {




            $data['header'] = $this->load->controller('common/snippets/header');
            $data['nav'] = $this->load->controller('common/snippets/navbar');
            $data['sidebar'] = $this->load->controller('common/snippets/sidenav');
            $data['footer'] = $this->load->view('common/footer');

            return $this->load->view('calendar/index', $data);
        } else {
            $this->redirect('/admin');
        }
    }
}