<?php

class ControllerCommonSnippets extends Controller {

    public function header() {
        $this->head->addLinks([
            'rel' => 'stylesheet',
            'href' => "https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css",
            'integrity' => "sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4",
            'crossorigin' =>"anonymous"
        ]);

        $this->head->addLinks('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        $this->head->addLinks($this->url->root . '/public/css/master.css');

        $this->head->addScript($this->url->root . '/public/js/jquery.min.js');
        $this->head->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
        $this->head->addScript('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js');

        $this->head->addLinks($this->url->root . '/public/css/admin.css');
        $this->head->addScript($this->url->root . '/public/js/admin.js');

        $this->head->addScript('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js');
        $this->head->addScript('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js');
        $this->head->addLinks('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css');

        $data['scripts'] = $this->head->getScripts();
        $data['links'] = $this->head->getLinks();

        return $this->load->view('common/header', $data);
    }

    public function navbar($title = '') {
        if(empty($title))
            $title = '';

        $model = $this->load->model('account/user');
        $user = $model->getUserById($_SESSION['user_id']);

        $model = $this->load->model('common/index');
        $data['weather'] = $model->getUserWeatherData('full');
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