<?php

class ModelMailMain extends Model {

    public function storeMail($data) {
        $query =
            "INSERT INTO `" . DB_PREFIX . "messages` " .
            "(`" . implode("`, `", array_keys($data)) . "`) " .
            "VALUES " .
            "(:" . implode(", :", array_keys($data)) . ");";

        $stmt = $this->db->prepare($query);
        return $stmt->execute($data) ? true : false;
    }
}