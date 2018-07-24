<?php

class ModelCommonTeam extends Model {

    public function getActiveTeam() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."team` WHERE `active` = 1")->fetchAll(PDO::FETCH_ASSOC);
    }
}