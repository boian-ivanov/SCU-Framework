<?php

class ModelSettingsSettings extends Model {

    public function getSettings($limit = 10) {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."settings` WHERE `displayed` = 1 LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSettingData() {
        $key = 'general_settings';
        $stmt = $this->db->prepare("SELECT * FROM `" . DB_PREFIX . "settings` WHERE `key` = :key");
        $stmt->execute(['key' => $key]);
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($return)) return false;
        $return['data'] = json_decode($return['data'], true);
        return $return;
    }
}