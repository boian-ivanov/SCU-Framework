<?php

class ModelSettingsSettings extends Model {

    public function getSettings($limit = 10) {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."settings` WHERE `displayed` = 1 LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSettingData($id) {
        $stmt = $this->db->prepare("SELECT * FROM `" . DB_PREFIX . "settings` WHERE `displayed` = 1 AND `settings_id` = :id");
        $stmt->execute(['id' => $id]);
        $return = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($return)) return $return;
        $return['data'] = json_decode($return['data'], true);
        return $return;
    }
}