<?php

class ModelSettingsTeam extends Model {

    public function getMembers() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."team`")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMemberData($id) {
        $stmt = $this->db->prepare("SELECT * FROM `" . DB_PREFIX . "team` WHERE `member_id` = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMemberData($id, $data) {
        if($this->teamMemberExists($id)) {
            $query = "UPDATE `" . DB_PREFIX . "team` SET ";
            foreach($data as $key => $datum) {
                $query .= "`$key` = :$key,";
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

    public function addMember($data) {
        $query = "INSERT INTO `" . DB_PREFIX . "team` SET ";
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

    public function deleteMember($id) {
        $query = "DELETE FROM `" . DB_PREFIX . "team` WHERE member_id = :id ;";

        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id'=>$id]);
    }

    private function teamMemberExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) count FROM `" . DB_PREFIX . "team` WHERE `member_id` = :id");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchObject();
        return $res->count > 0;
    }
}