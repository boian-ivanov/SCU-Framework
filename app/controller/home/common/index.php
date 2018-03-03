<?php

class ControllerCommonIndex extends Controller{
    private $registry;

    public function __construct($registry){
        parent::__construct();
        $this->registry = $registry;
    }

    public function index() {
        /*$model = $this->load->model('common/index');
        $model->index();*/
//        $this->registry->set('foo', 'bar');

        $data['header'] = $this->load->controller('common/header/index');

        $data['slider'] = $this->slider();
        $data['for_us'] = $this->for_us();

        $data['footer'] = $this->load->controller('common/footer/index');

        return $this->load->view('common/index', $data);
    }

    private function slider() {
        $path = '/public/images/slider/';
        $data['images'] = [
            $path.'img_1.jpg',
            $path.'img_2.jpg',
            $path.'img_3.jpg'
        ];
        return $this->load->view('common/slider', $data);
    }

    private function for_us() {
        $data['people'][0] = [
            'name' => 'Rumen Gradinarov',
            'description' => 'Занимава се предимно в сферата на протетичната стоматология-снемаеми и неснемаеми конструции, терапия- кариесология, ендодонтия и естетично възстановяване на разрушени зъби.',
            'img' => ''
        ];
        $data['people'][1] = [
            'name' => 'Rumen Gradinarov',
            'description' => 'Занимава се предимно в сферата на протетичната стоматология-снемаеми и неснемаеми конструции, терапия- кариесология, ендодонтия и естетично възстановяване на разрушени зъби.',
            'img' => ''
        ];
        $data['people'][2] = [
            'name' => 'Rumen Gradinarov',
            'description' => 'Занимава се предимно в сферата на протетичната стоматология-снемаеми и неснемаеми конструции, терапия- кариесология, ендодонтия и естетично възстановяване на разрушени зъби.',
            'img' => ''
        ];

        return $this->load->view('common/addons/for_us', $data);
    }
}