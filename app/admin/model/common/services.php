<?php

class ModelCommonServices extends Model {

    public function getServices($page = 0) {
        $per_page = 10;
        $query = "SELECT `service_id`, `text`, `description`, `active`, `icon` FROM `" . DB_PREFIX . "services`";
        $start = (int)($page * $per_page);
        $query .= " LIMIT $start, $per_page;";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addService($data) {
        $query = "INSERT INTO `" . DB_PREFIX . "services` SET ";
        foreach($data as $key => $datum) {
            $query .= "`$key` = :$key,";
        }
        if(substr($query, -1) === ',') $query = substr($query, 0, -1);
        $stmt = $this->db->prepare($query);
        if($stmt->execute($data)) {
            return $this->db->lastInsertId();
        } else {
            return 0;
        }
    }

    public function getServiceData($id) {
        $query = "SELECT * FROM `" . DB_PREFIX . "services` WHERE `service_id` = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateServiceData($id, $data) {
        if($this->serviceExists($id)) {
            $query = "UPDATE `" . DB_PREFIX . "services` SET ";
            foreach($data as $key => $datum) {
                $query .= "`$key` = :$key,";
            }
            if(substr($query, -1) === ',') $query = substr($query, 0, -1);
            $query .= " WHERE `service_id` = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute(array_merge($data, ['id'=>$id]));
        }
        throw new Exception("Service with id '$id' does not exist.");
    }

    public function removeService($id) {
        $query = "DELETE FROM `" . DB_PREFIX . "services` WHERE service_id = :id;";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id'=>$id]);
    }

    private function serviceExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) count FROM `" . DB_PREFIX . "services` WHERE `service_id` = :id");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchObject();
        return $res->count > 0;
    }
}