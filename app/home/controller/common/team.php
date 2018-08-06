<?php

class ControllerCommonTeam extends Controller {

    public function __rewrite() {
        return array(
            "index" => "/member_id"
        );
    }

    public function index() {
        $data['header'] = $this->load->controller('common/header/index');
        $data['footer'] = $this->load->controller('common/footer/index');

        $model = $this->load->model('common/team');

        $data['member'] = $model->getTeamMemberById($this->request->get['member_id']);

        return $this->load->view('common/team', $data);
    }
}