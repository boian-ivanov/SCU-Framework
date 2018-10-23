<?php

class ControllerCommonFooter extends Controller {

    public function index() {

        $data = array();

        $address_1 = 'Bulevard Stefan Stambolov 73, 412,';
        $address_2 = '8000 Burgas, Bulgaria';
        $phone = '087 713 3257';
        $mail = 'info@easydent.bg';

        $data['left_column'] = array(
            'fa-map-marker' => "<span>$address_1</span>$address_2",
            'fa-phone' => $phone,
            'fa-envelope' => "<a href=\"mailto:$mail\">$mail</a>"
        );

        $data['about'] = 'Lorem ipsum dolor sit amet, consectateur adispicing elit. Fusce euismod convallis velit, eu auctor lacus vehicula sit amet.';

        $data['social_links'] = array(
            'fa-facebook' => 'https://www.facebook.com/eazydent/',
        );

        if($this->classLoaded()) {
            return $this->load->view('common/footer', $data);
        } else {
            $this->load->controller('error/not_found/index');
        }
    }
}