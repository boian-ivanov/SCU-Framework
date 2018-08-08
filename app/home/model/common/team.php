<?php

class ModelCommonTeam extends Model {

    public function getActiveTeam() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."team` WHERE `active` = 1")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamMemberById($id) {
        $query = "SELECT * FROM `" . DB_PREFIX . "team` WHERE `member_id` = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}