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
}