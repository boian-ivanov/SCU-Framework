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

    public function create($userData) {
        $insertData = array(
            'email' => $userData['email'],
            'passcode' => $userData['password_hash']
        );

        if(isset($userData['display_name'])) $insertData['display_name'] = $userData['display_name'];
        if(isset($userData['status']))       $insertData['status'] = $userData['status'];

        $insertData['token'] = $this->generateToken($userData);

        $query =
            "INSERT INTO `" . DB_PREFIX . "users` " .
            "(`" . implode("`, `", array_keys($insertData)) . "`) " .
            "VALUES " .
            "(:" . implode(", :", array_keys($insertData)) . ");";

        echo "<pre>" . __FILE__ . '-->' . __METHOD__ . ':' . __LINE__ . PHP_EOL;
        var_dump($userData, $insertData, $query);
        die();

        $stmt = $this->db->prepare($query);
        if($stmt->execute($insertData)) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    private function generateToken($userData) {
        return hash('sha256',
            md5(date('Y-m-d H:i:s', strtotime('now'))) .
            hash('sha1', strrev($userData['email'])) .
            base64_encode(
                rand(
                    rand(0,1000000),
                    rand(0,1000000)
                )
            )
        );
    }
}