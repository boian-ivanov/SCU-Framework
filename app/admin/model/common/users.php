<?php

class ModelCommonUsers extends Model {

    public function getUsers($page = 0) {
        $per_page = 10;
        $query = "SELECT `user_id`, `email`, `display_name`, `status` FROM `" . DB_PREFIX . "users`";
        $start = (int)($page * $per_page);
        $query .= " LIMIT $start, $per_page;";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserStatus() {
        $query = "SELECT * FROM `" . DB_PREFIX . "user_status`";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}