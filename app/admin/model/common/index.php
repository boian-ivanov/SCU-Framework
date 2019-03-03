<?php

class ModelCommonIndex extends Model {
    private $location;

    /*public function index() {
        $query = $this->db->query("SELECT * FROM `test`")->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>' . __FILE__ . ' : ' . __LINE__ . ' -> ' . __METHOD__ . '<br>';
        var_dump($query);
        die();
    }*/

    public function getWelcomeMessage() {
        $location = $this->getUserLocation();

        $date = new DateTime(null, isset($location['timezone']) ? new DateTimeZone($location['timezone']) : null);
        
        $hour = $date->format('H');
        if($hour > 0 && $hour <= 12)
            $welcome = "Good morning";
        else if($hour > 12 && $hour <= 17)
            $welcome = "Good afternoon";
        else
            $welcome = "Good evening";

        $welcome .= ", %s!";

        if($location) {
            $weather = $this->getUserWeatherData($location);

            $icon = $weather['weather'][0]['icon'] ? "<img src='http://openweathermap.org/img/w/" . $weather['weather'][0]['icon'] . ".png'>" : false;

            $welcome .=  " Currently at " . $location['city'] . " the date is " . $date->format('l jS \of F Y') . " and the weather is set to be " . $weather['weather'][0]['main'] . " with " . $weather['weather'][0]['description'] . ".$icon";
        }

        return $welcome;
    }

    public function getUserWeatherData($location = null) {
        $appid = 'e4834a68505cb92baf641b01d35d63fd';
        $loc = $location ?? $this->getUserLocation();
        $json = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lon=".$loc['lon']."&lat=".$loc['lat']."&units=metric&appid=$appid");
        return json_decode($json, true) ?? false;
    }

    public function getUserLocation() {
        $json = file_get_contents("http://ip-api.com/json/" . $this->get_client_ip());
        $json = json_decode($json, true);

        return $json;
    }

    private function get_client_ip() {
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
        else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '::1')
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = false;
        return $ipaddress;
    }

    public function getLatestMessages() {
        return $this->db->query(
            "SELECT * FROM `".DB_PREFIX."messages` " .
            "WHERE checked_by IS NULL"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkMessageById($message_id, $user_id) {
        $query = "UPDATE `" . DB_PREFIX . "messages` SET `checked_by` = :user_id WHERE `message_id` = :message_id";
        $stmt = $this->db->prepare($query);
        $return = $stmt->execute([
            'message_id' => $message_id,
            'user_id'    => $user_id
        ]);

        if($return) {
            return $return;
        } else {
            return $stmt->errorCode();
        }
    }

    public function getNavItems() {
        /*$arr =[
            [
                'name' => 'Dashboard',
                'link' => '/admin',
                'icon' => 'fa-dashboard'
            ],
            [
                'name' => 'Calendar',
                'link' => '/admin/calendar',
                'icon' => 'fa-calendar'
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