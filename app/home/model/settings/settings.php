<?php

class ModelSettingsSettings extends Model {

    public function getSettingData($code, $key = '') {
        $query = "SELECT `key`, `data` FROM `" . DB_PREFIX . "settings` WHERE `code` = :code";
        $params = ['code' => $code];

        if($key != '') {
            if(is_array($key)) {
                $query .= " AND `key` IN (:" . implode(', :', array_keys($key)) . ")";
                foreach($key as $k => $v) $params[":$k"] = $v;
            } else {
                $query .= " AND `key` = :key;";
                $params['key'] = $key;
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return[$row['key']] = json_decode($row['data'], true);
        }
        return isset($return) ? $return : false;
    }
}