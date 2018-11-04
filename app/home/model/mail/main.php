<?php

class ModelMailMain extends Model {

    public function storeMail($data) {
        $query =
            "INSERT INTO `" . DB_PREFIX . "messages` " .
            "(`" . implode("`, `", array_keys($data)) . "`) " .
            "VALUES " .
            "(:" . implode(", :", array_keys($data)) . ");";

        $stmt = $this->db->prepare($query);
        if($stmt->execute($data)) {
            return $this->sendMail($data);
        }
        return false;
    }

    public function sendMail($data) {
        $from = 'mail@easydent.bg';
        $to = $data['email'];
        $subject = 'Testing php mail';
        $message = $data['message'];
        $headers = "From:".$from;
        return mail($to,$subject,$message, $headers);
    }
}