<?php

class ModelSettingsTeam extends Model {

    public function getMemberData($id) {
        $stmt = $this->db->prepare("SELECT * FROM `" . DB_PREFIX . "team` WHERE `member_id` = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMemberData($id, $data) {
        if($this->teamMemberExists($id)) {
            $allowed = ["name", "short_description", "description", "additional_data"];

            $query = "UPDATE `" . DB_PREFIX . "team` SET ";
            foreach($data as $key => $datum) {
                if(in_array($key, $allowed)) {
                    $query .= "`$key` = :$key,";
                }
            }
            if(substr($query, -1) === ',') $query = substr($query, 0, -1);
            $query .= " WHERE `member_id` = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute(array_merge($data, ['id'=>$id]));
        }
        throw new Exception("User with id '$id' does not exist.");
    }

    public function updateMemberImage($id, $imagePath) {
        if($this->teamMemberExists($id)) {
            $stmt = $this->db->prepare("UPDATE `" . DB_PREFIX . "team` SET `image` = :image WHERE `member_id` = :id");
            return $stmt->execute(['image'=> $imagePath , 'id' => $id]);
        }
        throw new Exception("User with id '$id' does not exist.");
    }

    private function teamMemberExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) count FROM `" . DB_PREFIX . "team` WHERE `member_id` = :id");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchObject();
        return $res->count > 0;
    }
}