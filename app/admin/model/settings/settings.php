<?php

class ModelSettingsSettings extends Model {

    public function getSettingData($code) {
        $stmt = $this->db->prepare("SELECT `key`, `data` FROM `" . DB_PREFIX . "settings` WHERE `code` = :code");
        $stmt->execute(['code' => $code]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return[$row['key']] = json_decode($row['data'], true);
        }
        return isset($return) ? $return : false;
    }

    public function updateSettings($code, $data) {
        $insert_stmt = $this->db->prepare(
            "INSERT INTO `" . DB_PREFIX . "settings` " .
            "SET `key` = :key, `data` = :data, `code` = :code;"
        );
        $update_stmt = $this->db->prepare(
            "UPDATE `" . DB_PREFIX . "settings` ".
            "SET `data` = :data " .
            "WHERE `code` = :code AND `key` = :key;"
        );
        foreach ($data as $key => $value) {
            $params = array(
                'code' => $code,
                'key' => $key,
                'data' => json_encode($value)
            );
            if($this->getSettingExists($code, $key))
                $update_stmt->execute($params);
            else
                $insert_stmt->execute($params);
        }
        return true;
    }

    public function getSettingExists($code, $key) {
        $stmt = $this->db->prepare("SELECT COUNT(*) count FROM `" . DB_PREFIX . "settings` WHERE `code` = :code AND `key` = :key");
        $stmt->execute([
            'code' => $code,
            'key' => $key
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['count'] > 0;
    }
}