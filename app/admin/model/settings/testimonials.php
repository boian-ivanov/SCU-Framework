<?php

class ModelSettingsTestimonials extends Model {

    public function getTestimonials() {
        return $this->db->query("SELECT * FROM `".DB_PREFIX."testimonials`")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTestimonialData($id) {
        $query = "SELECT * FROM `" . DB_PREFIX . "testimonials` WHERE `testimonial_id` = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTestimonialData($id, $data) {
        if($this->testimonialExists($id)) {
            $query = "UPDATE `" . DB_PREFIX . "testimonials` SET ";
            foreach($data as $key => $datum) {
                $query .= "`$key` = :$key,";
            }
            if(substr($query, -1) === ',') $query = substr($query, 0, -1);
            $query .= " WHERE `testimonial_id` = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute(array_merge($data, ['id'=>$id]));
        }
        throw new Exception("Testimonial with id '$id' does not exist.");
    }

    public function updateTestimonialImage($id, $imagePath) {
        if($this->testimonialExists($id)) {
            $stmt = $this->db->prepare("UPDATE `" . DB_PREFIX . "testimonials` SET `image` = :image WHERE `testimonial_id` = :id");
            return $stmt->execute(['image'=> $imagePath , 'id' => $id]);
        }
        throw new Exception("Testimonial with id '$id' does not exist.");
    }

    public function addTestimonial($data) {
        $query = "INSERT INTO `" . DB_PREFIX . "testimonials` SET ";
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

    public function deleteTestimonial($id) {
        $query = "DELETE FROM `" . DB_PREFIX . "testimonials` WHERE testimonial_id = :id ;";

        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id'=>$id]);
    }

    private function testimonialExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) count FROM `" . DB_PREFIX . "testimonials` WHERE `testimonial_id` = :id");
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchObject();
        return $res->count > 0;
    }
}