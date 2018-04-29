<?php

class ModelAccountUser extends Model {
    public $user;

    public function findByEmail($email) {
        $query = "SELECT * FROM `" . DB_PREFIX . "users` WHERE `email` = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $this->user = $stmt->fetchObject();
        return $this->user;
    }

    public function getUserById($user_id) {
        $query = "SELECT * FROM `" . DB_PREFIX . "users` WHERE `user_id` = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $this->user = $stmt->fetchObject();
        return $this->user;
    }

    public function save($user_id, $user_details = array()) {
        if(!empty($user_details)) {
            $query = "UPDATE `" . DB_PREFIX . "users` SET ";
            foreach ($user_details as $key => $detail) {
                $query .= "$key = :$key";
                if(end($user_details) !== $detail) $query .= ', ';
            }
            $query .= " WHERE `user_id` = :user_id";

            $stmt = $this->db->prepare($query);
            $return = $stmt->execute(array_merge($user_details, ['user_id' => $user_id]));

            if($return) {
                return $return;
            } else {
                return $stmt->errorCode();
            }
        }
    }
}