<?php

class ControllerCommonSnippets extends Controller {

    public function header() {
        $links = [
            '/node_modules/bootstrap/dist/css/bootstrap.min.css',
            '/node_modules/font-awesome/css/font-awesome.min.css',
            '/node_modules/fullcalendar/dist/fullcalendar.min.css',
            $this->url->root . '/public/css/master.css',
            $this->url->root . '/public/css/styles-extended.css',
            $this->url->root . '/public/css/admin.css'
        ];

        $scripts = [
            '/node_modules/jquery/dist/jquery.min.js',
            '/node_modules/popper.js/dist/umd/popper.min.js',
            '/node_modules/bootstrap/dist/js/bootstrap.min.js',
            '/node_modules/moment/moment.js',
            '/node_modules/fullcalendar/dist/fullcalendar.min.js',
            $this->url->root . '/public/js/admin.js',
//            $this->url->root . '/public/js/speed.js'
        ];

        $data['links'] = $this->head->addLinks($links);
        $data['scripts'] = $this->head->addScripts($scripts);

        return $this->load->view('common/head', $data);
    }

    public function navbar($title = '') {
        if(empty($title))
            $title = '';

        $model = $this->load->model('account/user');
        $user = $model->getUserById($_SESSION['user_id']);

        $model = $this->load->model('common/index');
        $data['weather'] = $model->getUserWeatherData();
        $data['display_name'] = $user->display_name;
        $data['title'] = $title;

        return $this->load->view('common/nav', $data);
    }

    public function sidenav() {
        $model = $this->load->model('common/index');

        $data['nav_items'] = $model->getNavItems();

        return $this->load->view('common/sidebar', $data);
    }
}