<?php

class ModelCommonUsers extends Model {

    public function getUsers() {
        $query = "SELECT user_id, email, display_name, status FROM `" . DB_PREFIX . "users`";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}