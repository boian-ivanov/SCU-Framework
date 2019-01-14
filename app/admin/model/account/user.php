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

    public function getUserPermissions($user_id) {
        $query =
            "SELECT `status_message` FROM `" . DB_PREFIX . "users` u " .
            "LEFT JOIN `" . DB_PREFIX . "user_status` us ON us.status_id = u.status  WHERE `user_id` = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['status_message'];
    }

    public function getUserCount($status = '') {
        $query = "SELECT COUNT(*) as `count` FROM `".DB_PREFIX."users`";
        if($status != '') {
            $query .= " u RIGHT JOIN `tst_user_status` us ON u.status = us.status_id WHERE us.status_message = '$status'";
        }
        $res = $this->db->query($query);
        return (int)$res->fetchColumn();
    }

    public function deleteByUserId($user_id) {
        $query = "DELETE FROM `".DB_PREFIX."users` WHERE `user_id` = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->rowCount() > 0;
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

    public function statusExists($status_id) {
        $query = "SELECT COUNT(*) count FROM `" . DB_PREFIX . "user_status` WHERE status_id = :status_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['status_id' => $status_id]);
        return (int)$stmt->fetchColumn() > 0;
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