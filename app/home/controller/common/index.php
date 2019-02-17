<?php

class ControllerCommonIndex extends Controller {
    /*private $registry;

    public function __construct($registry){
        parent::__construct();
        $this->registry = $registry;
    }*/

    /*public function __rewrite() {
        return array(
            'test' => '/id',
            'index' => '/id',
        );
    }

    public function test() {
        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($_GET);
        die();
    }*/

    public function index() {
        /*$model = $this->load->model('common/index');
        $model->index();*/
//        $this->registry->set('foo', 'bar');
        $data['heading'] = 'Easy Dent';

        $data['header'] = $this->load->controller('common/header/index', $data);

        $data['slider'] = $this->slider();
        $data['services'] = $this->services();
        $data['team'] = $this->ourTeam();
        $data['testimonials'] = $this->testimonials();
        $data['cards'] = $this->cards();

        $model = $this->load->model('settings/settings');

        $settings = $model->getSettingData('general_settings'); // add settings key

        $data['map'] = $this->load->view('contact/map');
        $data['form'] = $this->load->view('contact/form', $settings);

        $data['seperator'] = $this->load->view('common/addons/seperator');

        $data['footer'] = $this->load->controller('common/footer/index');

        return $this->load->view('common/index', $data);
    }

    private function cards() {
        $model = $this->load->model('settings/settings');

        $data = $model->getSettingData('general_settings', ['card1', 'card2', 'card3']);

        return $this->load->view('common/addons/cards', $data);
    }

    private function slider() {
        $path = '/public/images/slider/';
        $data['images'] = [
            $path . 'img_1.jpg',
            $path . 'img_2.jpg',
            $path . 'img_3.jpg'
        ];

        return $this->load->view('common/slider', $data);
    }

    private function services() {
        $model = $this->load->model('common/index');
        $data['services'] = $model->getServices();

        if ($data['services'] && !empty($data['services'])) {
            return $this->load->view('common/addons/services', $data);
        }
        return false;
    }

    /*private function actions() {

        for ($i = 0; $i < 4; $i++) {
            $data['columns'][] = [
                'icon' => 'fa-smile-o',
                'title'  => 'What we do',
                'text'   => 'Ubi est emeritis habitio?Sunt monses captis clemens, barbatus indictioes.Tabes peregrinationes, tanquam altus zelus.',
                'button' => 'Show more',
                'link'   => 'catalog'
            ];
        }

        return $this->load->view('common/addons/actions', $data);
    }*/

    private function testimonials() {
        $data['heading'] = 'Доволни клиенти';

        $model = $this->load->model('common/index');

        $data['testimonials'] = $model->getTestimonials();
        $data['image_path'] = $this->url->root . '/public/images/testimonials/';

        return $this->load->view('common/testimonials', $data);
    }

    private function ourTeam() {
        $model = $this->load->model('common/team');

        $data['columns'] = $model->getActiveTeam();

        $data['team_link'] = $this->url->root . '/team/%s';
        $data['image_path'] = $this->url->root . "/public/images/profile_images/";

        return $this->load->view('common/addons/team', $data);
    }
}