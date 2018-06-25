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
//        $data['actions'] = $this->actions();
        $data['team'] = $this->ourTeam();
        $data['testimonials'] = $this->testimonials();
        $data['contact_form'] = $this->contact_form();
        $data['cards'] = $this->cards();
        $data['map'] = $this->map();

        $data['seperator'] = $this->load->view('common/addons/seperator');

        $data['footer'] = $this->load->controller('common/footer/index');

        return $this->load->view('common/index', $data);
    }

    private function cards() {

        return $this->load->view('common/addons/cards');
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

    private function actions() {

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
    }

    private function testimonials() {
        $data['heading'] = 'Testimonials';

        $i = 0;
        while($i++ < 5) {
            $data['testimonials'][] = [
                'name' => 'Lorem Ipsum, Burgas',
                'heading' => 'Eheu, teres idoleum!',
                'text' => 'Danistas crescere! Pol, regius ausus! Vae, flavum solem! Cum barcas credere, omnes hippotoxotaes carpseris mirabilis, bi-color liberies. A falsis, candidatus emeritis vortex. Nunquam manifestum genetrix. Nunquam pugna nomen. Victrixs trabem in rugensis civitas!',
                'image' => $this->url->root . '/public/images/testimonials/img_1.jpg'
            ];

        }

        return $this->load->view('common/testimonials', $data);
    }

    private function ourTeam() {
        for ($i = 0; $i < 3; $i++) {
            $data['columns'][] = [
                'title'  => 'Д-р Ц. Младенова',
                'text'   => 'Преобладаващ интерес към детската стоматология- занимава се с профилактика и превантивнто им лечение. Терапия, пародонтология и ендодонтия на постоянното съзъбие.',
                'button' => 'Show more',
                'link'   => 'catalog'
            ];
        }

        return $this->load->view('common/addons/team', $data);
    }

    private function map() {
        return $this->load->view('common/addons/map');
    }

    public function contact_form() {

        //$data[''] =

        return $this->load->view('common/addons/contact');
    }
}