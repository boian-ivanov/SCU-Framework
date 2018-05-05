<?php

class ModelCommonIndex extends Model {
    private $location;

    public function index() {
        /*$query = $this->db->query("SELECT * FROM `test`")->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>' . __FILE__ . ' : ' . __LINE__ . ' -> ' . __METHOD__ . '<br>';
        var_dump($query);
        die();*/
    }

    public function getWelcomeMessage() {
        $weather = $this->getUserWeatherData();
        $icon = "<img src='http://openweathermap.org/img/w/".$weather['icon'].".png'>";
        $location = $this->location ?? $this->getUserLocation();
        $date = date('l jS \of F Y');
        $hour = date('H');
        if($hour > 0 && $hour <= 12)
            $welcome = "Good morning";
        else if($hour > 12 && $hour <= 17)
            $welcome = "Good afternoon";
        else
            $welcome = "Good evening";

        return $welcome . ", %s! Currently at " . $location . " the date is " . $date . " and the weather is set to be " . $weather['main'] . " with " . $weather['description'] . ".$icon";
    }

    public function getUserWeatherData() {
        $appid = 'e4834a68505cb92baf641b01d35d63fd';
        $city = $this->getUserLocation();
        $json = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$appid"); // e4834a68505cb92baf641b01d35d63fd
        $json = json_decode($json, true);
        
        return $json['weather'][0];
    }

    public function getUserLocation() {
        $json  = file_get_contents("https://api.ipdata.co");
        $json  =  json_decode($json ,true);
        $city = $json['city'];
        return $this->location = $city;
    }

    /*private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }*/

    public function getNavItems() {
        /*$arr =[
            [
                'name' => 'Dashboard',
                'link' => '/admin',
                'icon' => 'fa-dashboard'
            ],
            [
                'name' => 'Calendar',
                'link' => '/calendar',
                'icon' => 'fa-calendar'
            ],
            [
                'name' => 'Radoslava',
                'link' => '/radi',
                'icon' => 'fa-paw',
                'submenu' => [
                    [
                        'name' => 'Radi',
                        'link' => '/radi',
                        'icon' => 'fa-paw'
                    ]
                ]
            ],
            [
                'name' => 'Users',
                'icon' => 'fa-address-card',
                'submenu' => [
                    [
                        'name' => 'SubItem1',
                    ],
                ]
            ],
        ];
        $query = $this->db->query("INSERT INTO `".DB_PREFIX."settings` (`key`,`data`) VALUES('sidebar_items','".json_encode($arr)."')");*/
        $res = $this->db->query("SELECT * FROM `".DB_PREFIX."settings` WHERE `key` = 'sidebar_items'")->fetch(PDO::FETCH_ASSOC);

        return json_decode($res['data'], true);
    }
}