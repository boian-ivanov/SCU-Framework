<?php

class ModelSettingsSettings extends Model {

    public function getSettings($limit = 10) {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."settings` LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
    }
}