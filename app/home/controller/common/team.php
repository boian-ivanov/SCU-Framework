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

        $data['image_path'] = $this->url->root . "/public/images/profile_images/";

        if(isset($this->request->get['member_id'])) {
            $data['member'] = $model->getTeamMemberBySlug($this->request->get['member_id']);
            if($data['member']['background_image'] != '' || $data['member']['background_image'] != null) {
                $data['background_image'] = $this->url->root . '/public/images/profile_images/' . $data['member']['background_image'];
            } else {
                $data['background_image'] = $this->url->root . '/public/images/team_member/background1.jpg';
            }

            if($data['member']) {
                return $this->load->view('team/team_member', $data);
            } else {
                $this->load->controller('error/not_found/index');
            }
        } else {
            $data['columns'] = $model->getActiveTeam();
            $data['team_link'] = $this->url->root . '/team/%s';

            $data['team'] = $this->load->view('common/addons/team', $data);

            return $this->load->view('team/team_listing', $data);
        }
    }
}